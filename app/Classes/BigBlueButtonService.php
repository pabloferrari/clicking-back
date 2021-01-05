<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use BigBlueButton;
use BigBlueButton\Parameters\{
    CreateMeetingParameters,
    EndMeetingParameters,
    JoinMeetingParameters,
    GetMeetingInfoParameters,
    HooksCreateParameters
};
use BigBlueButton\Responses\GetMeetingInfoResponse;



use App\Models\{MeetingType,MeetingRequest,Meeting,MeetingUser,Classroom,IntitutionClass,Teacher,Student,User};
use App\Classes\{Helpers,UserService,TeacherService,StudentService,ClassroomService,CourseClassService};
use DB;
use Log;

class BigBlueButtonService
{

    private $bbb;
    private $userService;
    private $teacherService;
    private $studentService;
    private $classroomService;

    public function __construct() {
        $this->bbb = new BigBlueButton();
        $this->userService = new UserService();
        $this->teacherService = new TeacherService();
        $this->studentService = new StudentService();
        $this->classroomService = new ClassroomService();
        $this->courseClassService = new CourseClassService();

    }

    /**
     * CREAR REGISTRO EN TABLA bbb_meeting_requests
     *
     */
    public function createMeetingRequest($meetingRequestData) {

        try {
            $params = Helpers::paramBuilder('MeetingRequest', $meetingRequestData);
            $params['user_id'] = Auth::user()->id; 
            $params['institution_id'] = Auth::user()->institution_id;
            $newMeetingRequest = MeetingRequest::create($params);
            Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting Request -> ' . json_encode($newMeetingRequest));
    
            return $newMeetingRequest;

        } catch (\Throwable $th) {
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' Error Meeting Request -> ' . json_encode($meetingRequestData) . ' -> ' . $th->getMessage());
            return false;
        }

    }

    /**
     * CREAR REGISTRO EN TABLA bbb_meetings
     *
     */
    public function createMeeting($meetingRequestData) {
        
        DB::beginTransaction();
        try {
            $meetingRequest = MeetingRequest::findOrFail($meetingRequestData['id']);
            $params = [];
            $params['meeting_request_id'] = $meetingRequest->id;
            $params['meetingId'] = "clicking-{$meetingRequest->model}-{$meetingRequest->model_id}-" . Str::random(16);
            $params['allowStartStopRecording'] = false;
            $params['attendeePW'] = Str::random(12);
            $params['autoStartRecording'] = true;
            $params['welcome'] = 'Bienvenido ' . $meetingRequest->title;
            $params['moderatorPW'] = Str::random(24);
            $params['name'] = $meetingRequest->title;
            $params['record'] = true;
            $params['hash'] = Str::random(36);

            $newMeeting = Meeting::create($params);

            $meetingRequest->created = 1;
            $meetingRequest->save();

            Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting Request -> ' . json_encode($newMeeting));
    
            DB::commit();
            $res = $this->buildMeeting($newMeeting);
            return $res;

        } catch (\Exception $th) {
            DB::rollback();
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' Error Meeting Request -> ' . json_encode($meetingRequestData) . ' -> ' . $th->getMessage());
            return false;
        }

    }

    /**
     * CREAR REGISTRO EN TABLA bbb_meeting_users COMO PARTICIPANTE
     *
     */
    public function createMeetingUsers($meeting) {
        
        $users = $this->getUsersByMeetingType($meeting);
        $response = [];
        DB::beginTransaction();
        try {

            $response[] = $this->createModeratorMeeting($meeting);
            foreach ($users as $user) {

                $newMeetingUserData = [
                    'user_id' => $user,
                    'meeting_id' => $meeting->id,
                    'clicking_token' => Str::random(64),
                    'meetingId' => $meeting->meetingId,
                    'password' => $meeting->attendeePW,
                    'type' => 'attendee',
                    'status' => 0,
                    'internalMeetingID' => $meeting->meetingId
                ];

                $newMeetingUser = MeetingUser::create($newMeetingUserData);
                $newMeetingUser['public_url'] = env('APP_URL').'/api/bigbluebutton/join-to-meeting?clicking_token=' . $newMeetingUserData['clicking_token'];
                $response[] = $newMeetingUser;
                Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting User -> ' . json_encode($newMeetingUser));
            }
            DB::commit();
            
        } catch (\Exception $th) {
            DB::rollback();
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' Error Meeting User -> ' . json_encode($users) . ' -> ' . $th->getMessage());
        }
        return $response;
        
    }

    /**
     * CREAR REGISTRO EN TABLA bbb_meeting_users COMO MODERADOR
     *
     */
    public function createModeratorMeeting($meeting) {

        $newMeetingModeratorData = [
            'user_id' => $meeting->meetingRequest->user_id,
            'meeting_id' => $meeting->id,
            'clicking_token' => Str::random(64),
            'meetingId' => $meeting->meetingId,
            'password' => $meeting->moderatorPW,
            'type' => 'moderator',
            'status' => 0,
            'internalMeetingID' => $meeting->meetingId
        ];

        $newMeetingModerator = MeetingUser::create($newMeetingModeratorData);
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting Moderator -> ' . json_encode($newMeetingModerator));
        $newMeetingModerator['public_url'] = env('APP_URL').'/api/bigbluebutton/join-to-meeting?clicking_token=' . $newMeetingModeratorData['clicking_token'];
        return $newMeetingModerator;
    }

    /**
     * OBTENER LOS USUARIOS SEGUN TIPO DE MEETING
     *
     */
    public function getUsersByMeetingType($meeting) {
        $users = [];
        try {
            $meetingType = MeetingType::where('id', $meeting->meetingRequest->meeting_type)->first();
            switch ($meetingType->type) {
                case 'classroom':
                    // FIND MODEL ID
                    $users = $this->classroomService->getUsersByClassroom($meeting->meetingRequest->model_id);
                    break;
                case 'class':
                    // FIND MODEL ID
                    $users = $this->courseClassService->getUsersByClass($meeting->meetingRequest->model_id);
                    break;    
                case 'user':
                    // FIND BY ids
                    $users = $this->userService->getUsersIds($meeting->meetingRequest->model_id);
                    break;
                case 'teacher':
                    // FIND BY ids
                    $users = $this->teacherService->getUsersIds($meeting->meetingRequest->model_id);
                    break;
                case 'student':
                    // FIND BY ids
                    $users = $this->studentService->getUsersIds($meeting->meetingRequest->model_id);
                    break;
                default:
                    Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' SWITCH ELSE -> ' . $meetingType->type . ' -> ' . json_encode($meeting));
                    break;
            }
        } catch (\Throwable $th) {
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' Error -> ' . json_encode($meeting) . ' -> ' . $th->getMessage());
        }
        return $users;
    }



    /**
     * CONECTAR CON BBB PARA ARMAR LA MEETING
     *
     */
    public function buildMeeting($newMeeting) {

        $meetingParams = new CreateMeetingParameters($newMeeting['meetingId'], $newMeeting['name']);
        $meetingParams->setAllowStartStopRecording($newMeeting['allowStartStopRecording']);
        $meetingParams->setAutoStartRecording($newMeeting['autoStartRecording']);
        $meetingParams->setRecord($newMeeting['record']);
        $meetingParams->welcome = $newMeeting['welcome'];
        
        $hook = new HooksCreateParameters('https://clicking.app/api/bigbluebutton/callback/'.$newMeeting['hash']);
        $hook->setMeetingId($newMeeting['meetingId']);
        $resposeBigBlueButton = $this->bbb->createMeeting($meetingParams);
        $hh = $this->bbb->hooksCreate($hook);
        
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' hook -> ' . $hh->getHookId() . ' ' . $hh->getMessage());
        
        $newMeeting['internalMeetingId'] = $resposeBigBlueButton->getInternalMeetingId();
        $newMeeting['parentMeetingId'] = $resposeBigBlueButton->getParentMeetingId();
        $newMeeting['createTime'] = $resposeBigBlueButton->getCreationTime();
        $newMeeting['attendeePW'] = $resposeBigBlueButton->getAttendeePassword();
        $newMeeting['moderatorPW'] = $resposeBigBlueButton->getModeratorPassword();
        $newMeeting['voiceBridge'] = $resposeBigBlueButton->getVoiceBridge();
        $newMeeting['dialNumber'] = $resposeBigBlueButton->getDialNumber();
        $newMeeting['createDate'] = $resposeBigBlueButton->getCreationDate();
        $newMeeting['duration'] = $resposeBigBlueButton->getDuration();
        $newMeeting['returncode'] = $resposeBigBlueButton->getReturnCode();
        $newMeeting['download_url'] = "https://bigbluebutton.clicking.app/download/presentation/" . $resposeBigBlueButton->getInternalMeetingId() . "/" . $resposeBigBlueButton->getInternalMeetingId() . ".mp4";
        
        $meetings = $this->bbb->getMeetings();
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' $meetings -> ' . gettype($meetings) . ' ' . json_encode($meetings));
        $newMeeting->save();
        $users = $this->createMeetingUsers($newMeeting);

        $meetingInfoRequest = new GetMeetingInfoParameters($newMeeting['meetingId'], $newMeeting['moderatorPW']);
        $meetingInfo = $this->bbb->getMeetingInfoUrl($meetingInfoRequest);
        
        return ['meetingUrl' => $this->bbb->getMeetingsUrl(), 'meetingInfo' => $meetingInfo, 'meeting' => $newMeeting, 'users' => $users];

    }

    /**
     * CONECTAR CON BBB PARA FINALIZAR MEETING
     *
     */
    public function endMeeting($meetingId) {
        
        $meeting = Meeting::where('meetingId', $meetingId)->first();
        $endParams = new EndMeetingParameters($meetingId, $meeting->moderatorPW);
        $ress = $this->bbb->endMeeting($endParams);
        if($ress->getReturnCode() == 'SUCCESS') {
            Log::channel('bbb')->info(__METHOD__ . ' ' . Helpers::lsi() . ' MeetingId ' . $meetingId . ' Ended: ' . $ress->getReturnCode() . ' ' . $ress->getMessage());
            $meeting->returncode = 'ENDED';
        } else {
            $meeting->returncode = 'Error -> ' . $ress->getMessage();
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' MeetingId ' . $meetingId . ' Ended: ' . $ress->getReturnCode() . ' ' . $ress->getMessage());
        }
        $meeting->save();
        return [
            'returnCode' => $ress->getReturnCode(),
            'messageKey' => $ress->getMessageKey(),
            'message' => $ress->getMessage()
        ];
    }

    /**
     * CONECTAR CON BBB PARA UNIRSE A UNA MEETING
     *
     */
    public function joinToMeeting($dataUser) {
        
        $meetingUser = MeetingUser::where('clicking_token', $dataUser['clicking_token'])->first();
        $user = $this->userService->getUser($meetingUser->user_id);
        $newUser = new JoinMeetingParameters($meetingUser->internalMeetingID, $user->name, $meetingUser->password);
        // JoinMeetingParameters: $meetingId, $username, $password
        $newUser->setRedirect(true);
        
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' NU -> ' . json_encode($newUser));
        return $this->bbb->getJoinMeetingURL($newUser);
        
    }


    public function testCreateMeetingUsers($id){
        $meetingUser = Meeting::where('id', $id)->first();
        return $this->createMeetingUsers($meetingUser);
    }

}