<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\{MeetingType,Classroom,IntitutionClass,Teacher,Student,User};
use App\Http\Requests\BigBlueButtonRequests\{
    CreateMeetingRequest,
    EndMeetingRequest
};
use App\Classes\BigBlueButtonService;
use Log;
use Redirect;

class BigBlueButtonController extends Controller
{

    private $bbb;
    public $bbbService;
    public $params;

    public function __construct(BigBlueButtonService $bigBlueButtonService) {

        $this->bbbService = $bigBlueButtonService;
        // $meetingParams = new CreateMeetingParameters('clicking-1234', 'Test Meeting');
        

        // $res = $this->bbb->createMeeting($meetingParams);

        // // $data = $this->bbb->getMeetingInfoUrl($meetingParams);
        // dd([
        //     'res' => $res,
        //     // 'data' => $data

        // ]);

    }

    public function callback(Request $request, $hash) {
        $data = $request->all();
        try {
            $event = json_decode($data['event'])[0];
            $timestamp = json_decode($data['timestamp']);
            $domain = json_decode($data['domain']);
            $eventData = $event->data;

            if($eventData->type != 'event')
            Log::channel('bbb')->error("BigBlueButtonController::callback $hash EVENT ${$eventData->type} " . ' ' . json_encode($eventData));

            switch ($eventData->id) {
                case 'user-joined':
                    $this->userHasJoined($eventData);
                    break;
                case 'user-left':
                    $this->userHasLeft($eventData);
                    break;
                
                case 'meeting-ended':
                    $this->meetingEnd($eventData);
                    break;
                
                case 'rap-archive-started':
                case 'rap-archive-ended':
                    $this->recordChange();
                    break;
                default:
                    # code...
                    break;
            }

            // Log::channel('bbb')->debug("BigBlueButtonController::callback $hash " . $eventData->type);
            
        } catch (\Throwable $th) {
            Log::channel('bbb')->error("BigBlueButtonController::callback $hash " . $th->getMessage() . ' ' . json_encode($data));
        }
        return true;
    }


    public function getMeetingTypes() {
        try {
            $roles = Auth::user()->roles;
            $meetingTypes = MeetingType::where('role', $roles[0]->slug)->get();
            Log::channel('bbb')->debug(__METHOD__ . ' ' . $this->lsi() . ' ' . $roles[0]->slug . ' -> ' . json_encode($meetingTypes));
        } catch (\Throwable $th) {
            Log::channel('bbb')->error(__METHOD__ . ' ' . $this->lsi() . ' ' . $th->getMessage());
            $meetingTypes = [];
        }
        return response()->json($meetingTypes);
    } 

    public function index() {

        $this->createMeeting();
        dd([
            'getApiVersion' => $bbb->getApiVersion(),
            'getCreateMeetingUrl' => $bbb->getCreateMeetingUrl()
        ]);

    }

    public function createMeeting(CreateMeetingRequest $request){

        $data = $request->all();
        
        $newMeetingRequest = $this->bbbService->createMeetingRequest($data);
        
        if(!$newMeetingRequest)
        return response()->json(['status' => 'error'], 500);

        $res = $this->bbbService->createMeeting($newMeetingRequest);
        
        return response()->json($res);
    }

    public function endMeeting(EndMeetingRequest $request) {

        $data = $request->all();
        $res = $this->bbbService->endMeeting($data['meetingId']);
        return response()->json($res);

    }

    public function testCreateMeetingUsers($id){
        $ress = $this->bbbService->testCreateMeetingUsers($id);
        return response()->json($ress);
    }

    public function joinToMeeting(Request $request) {
        $data = $request->query();
        $url = $this->bbbService->joinToMeeting($data);
        // return response()->json($res);
        return Redirect::to($url);
    } 
    
    public function userHasJoined($data) {
        $meetingId = $data->attributes->meeting->{"external-meeting-id"};
        $user = $data->attributes->user;
        $this->bbbService->userHasJoined($meetingId, $user);
    }

    public function userHasLeft($data) {
        $meetingId = $data->attributes->meeting->{"external-meeting-id"};
        $user = $data->attributes->user;
        $this->bbbService->userHasLeft($meetingId, $user);
    }

    public function meetingEnd($data) {
        $this->bbbService->meetingEnd($data->attributes->meeting);
    }
}
