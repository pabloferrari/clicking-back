<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class CourseClass extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'classes';

    protected $fillable = [
        'title',
        'description',
        'course_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }


    public function class()
    {
        return $this->hasMany(\App\Models\Assignment::class);
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class, 'class_id');
    }
}
