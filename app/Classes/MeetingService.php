<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use App\Models\{Meeting,MeetingUser,Classroom,IntitutionClass,Teacher,Student,User, Notification};
use App\Classes\{Helpers,UserService,TeacherService,StudentService,ClassroomService,CourseClassService,NotificationService};
use DB;
use Log;

class MeetingService
{

    private $userService;
    private $teacherService;
    private $notificationService;
    private $studentService;
    private $classroomService;

    public function __construct() {
        $this->userService = new UserService();
        $this->teacherService = new TeacherService();
        $this->studentService = new StudentService();
        $this->classroomService = new ClassroomService();
        $this->courseClassService = new CourseClassService();
        $this->notificationService = new NotificationService();

    }

    /**
     * CREAR REGISTRO EN TABLA bbb_meetings
     *
     */
    public function createMeeting($meetingRequestData) {

        
        DB::beginTransaction();
        try {
            
            $params = [];
            $params['link'] = $meetingRequestData['link'];
            $params['model'] = $meetingRequestData['model'];
            $params['model_id'] = $meetingRequestData['model_id'];
            // $params['ids'] = '[]';
            $params['minutes'] = isset($meetingRequestData['minutes']) ? $meetingRequestData['minutes'] : 60;
            $params['user_id'] = Auth::user()->id;
            $params['institution_id'] = Auth::user()->institution_id;
            $params['hash'] = Str::random(36);
            $newMeeting = Meeting::create($params);
            
            Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting Request -> ' . json_encode($newMeeting));
            $newMeeting->users = $this->createMeetingUsers($newMeeting);
            
            DB::commit();
            return $newMeeting;

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
        $users = $this->getUsersByMeetingModel($meeting);
        $response = [];
        
        foreach ($users as $user) {

            $hash = Str::random(64);
            $newMeetingUserData = [
                'user_id' => $user,
                'meeting_id' => $meeting->id,
                'joined' => false,
                'hash' => $hash,
                'public_url' => env('APP_URL').'/api/meeting?token=' . $hash
            ];

            $newMeetingUser = MeetingUser::create($newMeetingUserData);

            // CREATE NOTIFICATION
            $dataNotification['user_id'] = $user;
            $dataNotification['type'] = 'meeting';
            $dataNotification['title'] = 'Nueva Clase';
            $dataNotification['text'] = 'Ingresa a la clase';
            $dataNotification['url'] = $newMeetingUser['public_url'];
            $dataNotification['model_id'] = $newMeetingUser['id'];
            $this->notificationService->createNotification($dataNotification);

            $response[] = $newMeetingUser;
            Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Meeting User -> ' . json_encode($newMeetingUser));
        }
        
        return $response;

    }

    public function endMeeting($id) {
        $meeting = Meeting::where('id', $id)->first();
        $meeting->finished = true;
        $meeting->save();
        $meetUsers = MeetingUser::where('meeting_id', $meeting->id)->get()->pluck('id');
        $this->notificationService->closeMeetingNotification($meetUsers);
        return $meeting;
    }

    public function getUsersByMeetingModel($meeting) {
        $users = [];
        try {
            switch ($meeting->model) {
                case 'classroom':
                    // FIND MODEL ID
                    $users = $this->classroomService->getUsersByClassroom($meeting->model_id);
                    break;
                case 'class':
                    // FIND MODEL ID
                    $users = $this->courseClassService->getUsersByClass($meeting->model_id);
                    break;
                case 'user':
                    // FIND BY ids
                    $users = $this->userService->getUsersIds($meeting->model_id);
                    break;
                case 'teacher':
                    // FIND BY ids
                    $users = $this->teacherService->getUsersIds($meeting->model_id);
                    break;
                case 'student':
                    // FIND BY ids
                    $users = $this->studentService->getUsersIds($meeting->model_id);
                    break;
                default:
                    Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' SWITCH ELSE -> ' . $meeting->type . ' -> ' . json_encode($meeting));
                    break;
            }
        } catch (\Throwable $th) {
            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' Error -> ' . json_encode($meeting) . ' -> ' . $th->getMessage());
        }
        return $users;
    }

    public function joinToMeeting($token) {

        try {
            
            $meetingUser = MeetingUser::with('meeting')->where('hash', $token)->first();
            if($meetingUser->meeting->finished) {
                return env('APP_URL');
            }
            $meetingUser->joined = true;
            $meetingUser->save();
            Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' User Join to Meeting -> ' . json_encode($meetingUser));
            return $meetingUser->meeting->link;

        } catch (\Throwable $th) {

            Log::channel('bbb')->error(__METHOD__ . ' ' . Helpers::lsi() . ' ERROR User Join to Meeting -> ' . $th->getMessage() . ' token ' . $token);
            return env('APP_URL');

        }
    }
}
