<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'text',
        'url',
        'viewed',
        'finished',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
