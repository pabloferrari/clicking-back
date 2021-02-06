<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'message',
        'user_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function status()
    {
        return $this->belongsTo('App\Models\TicketStatus', 'status');
    }

}
