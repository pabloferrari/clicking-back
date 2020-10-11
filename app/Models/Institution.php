<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cuit',
        'image',
        'active',
        'plan_id',
        'city_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function plan()
	{
		return $this->belongsTo(\App\Models\Plan::class);
    }

    public function city()
	{
		return $this->belongsTo(\App\Models\City::class);
	}
}
