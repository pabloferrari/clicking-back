<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'title',
        'status_id',
        'event_type_id',
        'notes',
        'external_link',
        'creator_id',
        'start_date',
        'end_date'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\UserEvent');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'creator_id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\EventStatus', 'id', 'status_id');
    }

    public function type()
    {
        return $this->hasOne('App\Models\EventType', 'id', 'event_type_id');
    }


}
