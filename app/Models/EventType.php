<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;
    protected $table = 'event_type';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
