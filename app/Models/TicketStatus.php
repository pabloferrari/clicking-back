<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    use HasFactory;
    protected $table = 'ticket_status';
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }

}
