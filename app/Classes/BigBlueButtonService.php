<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use BigBlueButton;
use BigBlueButton\Parameters\{CreateMeetingParameters,JoinMeetingParameters};


use BigBlueButton\Responses\GetMeetingInfoResponse;

use App\Models\{MeetingType,MeetingRequest,Meeting,MeetingUser,Classroom,IntitutionClass,Teacher,Student,User};
use App\Classes\{Helpers,UserService,TeacherService,StudentService,ClassroomService};
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

    }


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

    public function createMeeting($meetingRequestData) {
        
        DB::beginTransaction();
        try {
            $meetingRequest = MeetingRequest::findOrFail($meetingRequestData['id']);
            $params = [];
            $params['meeting_request_id'] = $meetingRequest->id;
            $params['meetingId'] = "clicking-{$meetingRequest->model}-{$meetingRequest->model_id}-" . Str::random(16);
            $params['allowStartStopRecording'] = false;
            $params['attendeePW'] = Str::random(12);;
            $params['autoStartRecording'] = false;
            $params['welcome'] = 'Bienvenido a Clase 1';
            $params['moderatorPW'] = Str::random(24);;
            $params['name'] = $meetingRequest->title;
            $params['record'] = false;

            Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting Data -> ' . json_encode($params));
            
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

    public function createMeetingUsers($meeting) {
        
        $users = $this->getUsersByMeetingType($meeting);
        
        DB::beginTransaction();
        try {

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
                Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting User -> ' . json_encode($newMeetingUser));
            }
            DB::commit();
            return $users;

        } catch (\Exception $th) {
            DB::rollback();
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' Error Meeting User -> ' . json_encode($users) . ' -> ' . $th->getMessage());
            return false;
        }

    }

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
                    $users = $this->classService->getUsersByClass($meeting->meetingRequest->model_id);
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

    public function buildMeeting($newMeeting) {

        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' -> ' . json_encode($newMeeting));
        $meetingParams = new CreateMeetingParameters($newMeeting['meetingId'], $newMeeting['name']);
        
        $meetingParams->setAllowStartStopRecording($newMeeting['allowStartStopRecording']);
        $meetingParams->setAutoStartRecording($newMeeting['autoStartRecording']);
        $meetingParams->setRecord($newMeeting['record']);
        $meetingParams->welcome = $newMeeting['welcome'];
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' -> ' . json_encode($meetingParams));
        $resposeBigBlueButton = $this->bbb->createMeeting($meetingParams);
        
        // $meetingParams->attendeePW = $newMeeting['attendeePW'];
        // $meetingParams->moderatorPW = $newMeeting['moderatorPW'];
        
        $newMeeting['internalMeetingId'] = $resposeBigBlueButton->getInternalMeetingId();
        $newMeeting['parentMeetingId'] = $resposeBigBlueButton->getParentMeetingId();
        $newMeeting['createTime'] = $resposeBigBlueButton->getCreationTime();
        $newMeeting['attendeePW'] = $resposeBigBlueButton->getAttendeePassword();
        $newMeeting['moderatorPW'] = $resposeBigBlueButton->getModeratorPassword();
        $newMeeting['voiceBridge'] = $resposeBigBlueButton->getVoiceBridge();
        $newMeeting['dialNumber'] = $resposeBigBlueButton->getDialNumber();
        $newMeeting['createDate'] = $resposeBigBlueButton->getCreationDate();
        $newMeeting['duration'] = $resposeBigBlueButton->getDuration();

        $newMeeting->save();
        
        $this->createMeetingUsers($newMeeting);
        
    }

    public function testCreateMeetingUsers($id){
        $meetingUser = Meeting::where('id', $id)->first();
        return $this->createMeetingUsers($meetingUser);
    }

    public function joinToMeeting($dataUser) {

        $meetingUser = MeetingUser::where('clicking_token', $dataUser['clicking_token'])->first();
        $newUser = new JoinMeetingParameters($meetingUser['meetingId'], $meetingUser['username'], $meetingUser['password']);
        // $meetingId, $username, $password

    }

}