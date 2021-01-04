<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\{MeetingType,Classroom,IntitutionClass,Teacher,Student,User};
use App\Http\Requests\BigBlueButtonRequests\CreateMeetingRequest;
use App\Classes\BigBlueButtonService;
use Log;

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
        Log::channel('bbb')->info(__METHOD__ . ' ' . $hash . ' ' . json_encode($request->all()));
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

    public function testCreateMeetingUsers($id){
        $ress = $this->bbbService->testCreateMeetingUsers($id);
        return response()->json($ress);
    }

    public function joinToMeeting(Request $request) {
        $data = $request->query();
        $res = $this->bbbService->joinToMeeting($data);
        return response()->json($res);
    }   
}
