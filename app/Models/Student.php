<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'active',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'email'
    ];

    public function classroomStudents()
    {
        return $this->hasMany(\App\Models\ClassroomStudent::class);
    }
}
