<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
		'id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'turn_id',
        'institution_year_id'
    ];

    public function turn() {
        return $this->belongsTo(\App\Models\Turn::class);
    }
    public function institution_year() {
        return $this->belongsTo(\App\Models\InstitutionsYear::class);
    }

    public function teachers() {
        return $this->belongsToMany(\App\Models\Teacher::class, 'commissions_teachers',
      'commission_id', 'teacher_id');
    }
}
