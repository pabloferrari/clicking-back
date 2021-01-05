<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntitutionClass extends Model
{
    use HasFactory;
    protected $table = 'classes';

    public function course()
    {
        return $this->hasOne(\App\Models\Course::class, 'id', 'course_id');
    }
}
