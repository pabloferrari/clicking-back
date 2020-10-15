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

    public function commissions() {
        return $this->belongsToMany(\App\Models\Commission::class, 'commissions_teachers', 
      'teacher_id', 'commission_id');
    }

    public function turns() {
        return $this->belongsToMany(\App\Models\Turn::class, 'teachers_turns', 
        'teacher_id', 'turn_id');
        
    }
}
