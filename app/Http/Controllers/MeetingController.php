<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\{Meeting,Classroom,IntitutionClass,Teacher,Student,User};
use App\Http\Requests\MeetingRequests\{
    CreateMeetingRequest,
    EndMeetingRequest
};
use App\Classes\MeetingService;
use Log;
use Redirect;

class MeetingController extends Controller
{

    private $meetingService;

    public function __construct(MeetingService $meetingService) {
        $this->meetingService = $meetingService;
    }

    public function createMeeting(CreateMeetingRequest $request){
        $data = $request->all();
        $newMeeting = $this->meetingService->createMeeting($data);
        return response()->json($newMeeting);
    }

    public function endMeeting(EndMeetingRequest $request) {

        $data = $request->all();
        $res = $this->meetingService->endMeeting($data['meetingId']);
        return response()->json($res);

    }

    public function joinToMeeting(Request $request) {
        $data = $request->query();
        $url = $this->meetingService->joinToMeeting($data['token']);
        return Redirect::to($url);
    }

    // public function meetingEnd($data) {
    //     $this->bbbService->meetingEnd($data->attributes->meeting);
    // }
}
