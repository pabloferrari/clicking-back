<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomStudent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'classroom_id'

    ];
    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
    public function assignmentStudent()
    {
        return $this->hasMany(\App\Models\StudentAssignment::class, 'classroom_student_id', 'id');
    }
}
