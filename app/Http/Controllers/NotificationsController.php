<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{NotificationService,Helpers,Socket};
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
        Socket::send('test', ['message' => 'Hello World!', 'user' => rand(1, 10)]);

        return response()->json(['data' => true]);
    }
}
