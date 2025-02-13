<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'subject_id',
        'teacher_id',
        'classroom_id',
        'course_type_id'
    ];

    public function courseClasses()
    {
        return $this->hasMany(\App\Models\CourseClass::class);
    }
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class);
    }

    public function courseType()
    {
        return $this->belongsTo(\App\Models\CourseType::class);
    }


    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function classroomStudents()
    {
        return $this->belongsTo(\App\Models\ClassroomStudent::class);
    }
}
