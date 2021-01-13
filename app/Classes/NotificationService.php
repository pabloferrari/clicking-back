<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;

use App\Models\{User, Notification};
use App\Classes\Helpers;

class NotificationService
{

    public static function getNotifications()
    {
        return Notification::where('user_id', Auth::user()->id)->where('finished', false)->get();
    }

    public static function getAll($id)
    {
        return Notification::where('user_id', Auth::user()->id)->get();
    }

    public static function createNotification($data)
    {
        $params = Helpers::paramBuilder('Notification', $data);
        $newNotification = Notification::create($params);
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Notification -> ' . json_encode($newNotification));
        return $newNotification;
    }


}
