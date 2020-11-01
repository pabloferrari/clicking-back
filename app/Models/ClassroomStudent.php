<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomStudent extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'student_id',
        'classroom_id'

    ];
    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function students()
    {

        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }
}
