<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'classroom_student_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class);
    }
    public function classroom_students()
    {
        return $this->hasMany(\App\Models\ClassroomStudent::class);
    }
}
