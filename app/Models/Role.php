<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

	protected $casts = [
		'level' => 'int'
	];

	protected $fillable = [
		'name',
		'slug',
		'description',
		'level'
	];

	public function users()
	{
		return $this->belongsToMany(\App\Models\User::class)
					->withPivot('id', 'deleted_at')
					->withTimestamps();
	}
}
