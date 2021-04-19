<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;

use App\Models\{Event, UserEvent, EventType, EventStatus};
use App\Classes\{Helpers, NotificationService};
use DB;


class EventService {

    private $notificationService;
    
    public function __construct() {
        $this->notificationService = new NotificationService();

    }

    public function getEvents() 
    {
        $eventsId = UserEvent::where('user_id', Auth::user()->id)->get()->pluck('event_id')->toArray();
        return Event::whereIn('id', $eventsId)->with(['status', 'creator' => function($query){
            $query->select('id','name', 'email', 'image');
        }, 'type', 'users', 'users.user' => function($query){
            $query->select('id','name', 'email', 'image');
        }, 'users.user.teacher', 'users.user.student'])->get();
    }

    public function getNextEvents()
    {
        $eventsId = UserEvent::where('user_id', Auth::user()->id)->get()->pluck('event_id')->toArray();
        return Event::whereIn('id', $eventsId)->with(['status', 'creator' => function($query){
            $query->select('id','name', 'email', 'image');
        }, 'type', 'users', 'users.user' => function($query){
            $query->select('id','name', 'email', 'image');
        }, 'users.user.teacher', 'users.user.student'])->where('start_date', '>', date("Y-m-d H:i"))->limit(5)->get();
    }

    public function getEvent($id) 
    {
        return Event::where('id', $id)
        ->with(['status', 'type', 'users', 'users.user' => function($query){
            $query->select('id','name', 'email', 'image');
        }, 'users.user.teacher', 'users.user.student'])->first();

    }

    public function getEventTypes() 
    {
        return EventType::get();
    }

    public function createEvent($data)
    {
        DB::beginTransaction();
        try {
            $params = Helpers::paramBuilder('Event', $data);
            $params['creator_id'] = Auth::user()->id;
            $params['event_type_id'] = $data['event_type'];
            $params['status_id'] = 1;
            Log::debug(__METHOD__ . ' ' . Helpers::lsi() . ' -> ' . json_encode($data));
            $newEvent = Event::create($params);

            // CREATE NOTIFICATION
            $dataNotification['user_id'] = Auth::user()->id;
            $dataNotification['type'] = 'event';
            $dataNotification['title'] = 'Nuevo evento';
            $dataNotification['text'] = $newEvent->title;
            $dataNotification['url'] = $newEvent->external_link ?? '';
            $dataNotification['model_id'] = $newEvent->id;
            $this->notificationService->createNotification($dataNotification);

            $newEvent->participants = (isset($data['guests'])) ? $this->createUserEvent($newEvent, $data['guests']) : $this->createUserEvent($newEvent);

            DB::commit();
            return Event::where('id', $newEvent->id)->with(['status', 'type', 'users', 'users.user'])->first();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' ' . Helpers::lsi() . ' ' . $e->getMessage() . ' -> ' . json_encode($data));
        }
    }

    public function createUserEvent($event, $users = []) 
    {
        Log::debug(__METHOD__ . ' ' . Helpers::lsi() . ' USERS -> ' . json_encode($users));
        $participants = [];
        $users = array_filter($users, function (int $i) { return $i != Auth::user()->id; });
        $participants[] = UserEvent::create(['event_id' => $event->id, 'user_id' => Auth::user()->id]);

        foreach ($users as $user) {
            $participants[] = UserEvent::create(['event_id' => $event->id, 'user_id' => $user]);
            // CREATE NOTIFICATION
            $dataNotification['user_id'] = $user;
            $dataNotification['type'] = 'event';
            $dataNotification['title'] = 'Nuevo evento';
            $dataNotification['text'] = $event->title;
            $dataNotification['url'] = $event->external_link ?? '';
            $dataNotification['model_id'] = $event->id;
            $this->notificationService->createNotification($dataNotification);
        }
        return $participants;
    }

}
