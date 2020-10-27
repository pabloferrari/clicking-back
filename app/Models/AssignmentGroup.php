<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_student_id',
        'assignment_id',
        'num'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function students()
    {
        return $this->belongsTo(\App\Models\Student::class);
    }
}
