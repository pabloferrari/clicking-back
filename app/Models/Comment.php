<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id',
        'user_id',
        'comment',
        'model_id',
        'model_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class, 'model_id');
    }

    public function assignment()
    {
        return $this->belongsTo(\App\Models\Assignment::class, 'model_id');
    }
}
