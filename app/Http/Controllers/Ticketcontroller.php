<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\TicketService;
use App\Http\Requests\TicketRequests\CreateTicketRequest;
use Log;


class Ticketcontroller extends Controller
{

    public $ticketService;
    public function __construct(TicketService $ticketService){
        
        $this->ticketService = $ticketService;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => $this->ticketService->getTickets()]);
    }

    public function getTicket($id)
    {
        return response()->json(['data' => $this->ticketService->getTicket($id)]);
    }

    

    public function store(CreateTicketRequest $request)
    {
        try {
            $newTicket = $this->ticketService->createTicket($request->all());
            Log::debug(__METHOD__ . ' - NEW TICKET CREATED ' . json_encode($newTicket));
            return response()->json($newTicket);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating ticket"], 400);
        }
    }
}
