<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;


    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class);
    }

    public function courseType()
    {
        return $this->belongsTo(\App\Models\CourseType::class);
    }

    public function classroom()
    {
        return $this->belongsTo(\App\Models\Classroom::class);
    }
}
