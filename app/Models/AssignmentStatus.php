<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class AssignmentStatus extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'assignment_status';
    protected $fillable = [
        'id',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // public function studentAssignments()
    // {
    //     return $this->belongsTo(\App\Models\StudentAssignment::class, 'assignment_status_id');
    // }

}
