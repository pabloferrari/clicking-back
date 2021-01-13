<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{NotificationService,Helpers};
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
}
