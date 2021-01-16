<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{NotificationService,Helpers,SocketService};
use Log;


class NotificationsController extends Controller
{
    public $notificationService;
    public function __construct(NotificationService $notificationService){
        $this->notificationService = $notificationService;

    }

    public function getNotifications (Request $request) {
        return response()->json(['data' => $this->notificationService->getNotifications()]);
    }

    public function testSocket(Request $request) {


        // Socket::send('notification', ['message' => 'Hello World!', 'user' => rand(1, 10)]);
        SocketService::send('notification', [ 'user' => 7, 'title' => 'Notification Title!', 'text' => 'notification text', 'url' => 'https://clicking.app/bigbluebutton/meeting/sssssss']);

        return response()->json(['data' => true]);
    }
}
