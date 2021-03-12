<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;

use App\Models\{User, Notification};
use App\Classes\{Helpers,SocketService};

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
        SocketService::send('notification', [ 'user' => $newNotification->user_id, 'title' => $newNotification->title, 'text' => $newNotification->text, 'url' => $newNotification->url]);
        Log::channel('bbb')->debug(__METHOD__ . ' ' . Helpers::lsi() . ' New Notification -> ' . json_encode($newNotification));
        return $newNotification;
    }

    // NOTIFICATIONS
    public function closeMeetingNotification($meetUsers) {
        try {
            $notifs = Notification::where('type', 'meeting')->whereIn('model_id', $meetUsers)->get();
            collect($notifs)->map(function($notif) {
                Log::debug(__METHOD__ . ' ' . Helpers::lsi() . " id: {$notif->id} " . json_encode($notif));
                $notif->finished = true;
                $notif->viewed = true;
                $notif->save();
                SocketService::send('notification', [ 'user' => $notif->user_id, 'title' => 'Meeting ended', 'text' => 'Meeting ended', 'url' => '']);
            });
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' ' . Helpers::lsi() . " id: " . json_encode($meetUsers) . ' ' . $th->getMessage());
        }
    }


}
