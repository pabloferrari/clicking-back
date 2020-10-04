<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'iso31662',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'country_id'
    ];

    public function country()
	{
		return $this->belongsTo(\App\Models\Country::class);
	}
}
