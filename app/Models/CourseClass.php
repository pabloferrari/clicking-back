<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [
        'title',
        'description',
        'course_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }

    public function class()
    {
        return $this->hasMany(\App\Models\Assignment::class);
    }
}
