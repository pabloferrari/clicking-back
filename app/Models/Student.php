<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'active',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'email',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function classroomStudents()
    {
        return $this->hasMany(\App\Models\ClassroomStudent::class);
    }
}
