<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'user_id',
        'active'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function courses() {
        return $this->belongsToMany(\App\Models\Commission::class, 'courses', 'teacher_id', 'id');
    }

    // public function shifts() {
    //     return $this->belongsToMany(\App\Models\Turn::class, 'teachers_shifts', 
    //     'teacher_id', 'shift_id');
        
    // }
}
