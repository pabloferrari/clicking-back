<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class StudentAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assignment_id',
        'classroom_student_id',
        'score',
        'limit_date',
        'assignment_status_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
        // 'classroom_student_id',

        // 'assignment_id',


    ];

    public function assignmentstatus()
    {
        return $this->belongsTo(\App\Models\AssignmentStatus::class, 'assignment_status_id');
    }

    public function assignments()
    {
        return $this->belongsTo(\App\Models\Assignment::class, 'assignment_id');
    }
    public function classroomstudents()
    {
        return $this->belongsTo(\App\Models\ClassroomStudent::class, 'classroom_student_id');
    }
}
