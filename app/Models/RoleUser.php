<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;
    protected $table = 'role_user';

	protected $casts = [
		'role_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'role_id',
		'user_id'
	];

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
