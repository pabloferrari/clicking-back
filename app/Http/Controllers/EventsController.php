<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\EventService;
use App\Http\Requests\EventRequests\CreateEventRequest;
use Log;

class EventsController extends Controller
{
    public $eventService;
    public function __construct(EventService $eventService){

        $this->eventService = $eventService;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => $this->eventService->getEvents()]);
    }

    public function getEventTypes() 
    {
        return $this->eventService->getEventTypes();
    }

    public function create(CreateEventRequest $request)
    {
        try {
            $newEvent = $this->eventService->createEvent($request->all());
            Log::debug(__METHOD__ . ' - NEW EVENT CREATED ' . json_encode($newEvent));
            return response()->json($newEvent);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating event"], 400);
        }
    }
}
