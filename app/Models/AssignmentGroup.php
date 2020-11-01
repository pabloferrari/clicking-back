<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'classroom_student_id',
        'assignment_id',
        'num'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'classroom_student_id',
        'assignment_id'
    ];

    public function students()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }

    public function assignment()
    {

        return $this->belongsTo(\App\Models\Assignment::class);
    }

    public function classroomtudents()
    {
        return $this->belongsTo(\App\Models\ClassroomStudent::class, 'classroom_student_id');
    }
}
