<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'class_id',
        'assignment_type_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'assignment_type_id',

        'class_id',

    ];

    public function class()
    {
        return $this->belongsTo(\App\Models\CourseClass::class);
    }

    public function assignmenttype()
    {
        return $this->belongsTo(\App\Models\AssignmentType::class, 'assignment_type_id');
    }

    public function studentAssignments()
    {
        return $this->belongsToMany(\App\Models\Assignment::class, 'student_assignments')->withPivot(
            'assignment_id',
            'classroom_student_id',
            'score',
            'assignment_status_id'
        )->withTimestamps();
    }

    public function studentsassignment()
    {
        return $this->hasMany(\App\Models\StudentAssignment::class);
    }
}
