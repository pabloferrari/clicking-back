<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventStatus extends Model
{
    use HasFactory;
    protected $table = 'event_status';
    protected $fillable = [
        'user_id',
        'event_id'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function event()
    {
        return $this->hasOne('App\Models\Event');
    }

    public function user()
    {
        return $this->hasOne('App\Models\Event');
    }
}
