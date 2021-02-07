<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;

use App\Models\{Event, UserEvent, EventType, EventStatus};
use App\Classes\{Helpers, NotificationService};
use DB;


class EventService {


    public function getEvents() 
    {
        $eventsId = UserEvent::where('user_id', Auth::user()->id)->get()->pluck('event_id')->toArray();
        $events = Event::whereIn('id', $eventsId)->with(['status', 'type', 'users', 'users.user'])->get();
        return $events;
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

            $newEvent = Event::create($params);

            $newEvent->participants = (isset($data['guests'])) ? $this->createUserEvent($newEvent, $data['guests']) : $this->createUserEvent($newEvent);

            DB::commit();
            return $newEvent;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' ' . Helpers::lsi() . ' ' . $e->getMessage() . ' -> ' . json_encode($data));
        }
    }

    public function createUserEvent($event, $users = []) 
    {
        $participants = [];
        $users = array_filter($users, function (int $i) { return $i != Auth::user()->id; });
        $participants[] = UserEvent::create(['event_id' => $event->id, 'user_id' => Auth::user()->id]);
        foreach ($users as $user) {
            $participants[] = UserEvent::create(['event_id' => $event->id, 'user_id' => $user]);
        }
        return $participants;
    }

}
