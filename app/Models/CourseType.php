<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class CourseType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'institution_id'
    ];

    public function institution()
    {
        return $this->belongsTo(\App\Models\Institution::class);
    }

    public function courses()
    {
        return $this->hasMany(\App\Models\Course::class);
    }
}
