<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Responses\GetMeetingInfoResponse;


class BigBlueButtonController extends Controller
{

    private $bbb;
    public $params;

    public function __construct() {

        $this->bbb = new BigBlueButton();
        $meetingParams = new CreateMeetingParameters('clicking-1234', 'Test Meeting');
        // dd($meetingParams);
        // $meetingParams = new CreateMeetingParameters($meetingID, $meetingName);
        $meetingParams->setModeratorPassword('moderatorPassword');
        $meetingParams->setAttendeePassword('attendeePassword');

        $res = $this->bbb->createMeeting($meetingParams);

        $data = $this->bbb->getMeetingInfoUrl($meetingParams);
        dd([
            'res' => $res,
            'data' => $data

        ]);

    }

    public function index() {

        $this->createMeeting();
        dd([
            'getApiVersion' => $bbb->getApiVersion(),
            'getCreateMeetingUrl' => $bbb->getCreateMeetingUrl()
        ]);

    }

    public function createMeeting(){

        $params = [];
        $params['allowStartStopRecording'] = false;
        $params['attendeePW'] = 'password';
        $params['autoStartRecording'] = false;
        $params['meetingID'] = 'clicking1234';
        $params['moderatorPW'] = 'moderatorPW';
        $params['name'] = 'clicking1234';
        $params['record'] = false;

        dd($this->params);
        $res = $this->bbb->createMeeting($params);
    }
}
