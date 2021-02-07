<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEvent extends Model
{
    use HasFactory;
    protected $table = 'user_events';
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
        return $this->hasOne('App\Models\Event', 'id', 'event_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

}
