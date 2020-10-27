<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'class_id',
        'assignment_type_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function class()
    {
        return $this->belongsTo(\App\Models\CourseClass::class);
    }

    public function assignmentType()
    {

        return $this->belongsTo(\App\Models\AssignmentType::class);
    }
    public function studentAssignments()
    {
        return $this->belongsToMany(
            \App\Models\StudentAssignment::class,
            'student_assignments',
            'assignment_id',
            'classroom_student_id'
        );
    }
}
