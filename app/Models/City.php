<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'zip_code'
    ];

    protected $hidden = [
        'province_id',
        'created_at',
        'updated_at',
    ];

    public function province()
	{
		return $this->belongsTo(\App\Models\Province::class);
    }
    
    public function institutions() {
        return $this->hasMany(\App\Models\Institution::class);
    }
}
