<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Log;
use Hash;

use App\Models\{Ticket, User};
use App\Classes\{Helpers};

class TicketService
{

    public function getTickets()
    {
        return Ticket::with(['status', 'user'])->orderBy('updated_at', 'desc')->get();
    }

    public function getTicket($id)
    {
        return Ticket::with(['status', 'user'])->where('id', $id)->first();
    }

    

    public function createTicket($data)
    {
        $params = Helpers::paramBuilder('Ticket', $data);
        $params['user_id'] = Auth::user()->id;
        $params['status'] = 1;
        return Ticket::create($params);
    }


}