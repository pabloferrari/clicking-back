<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use \Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class)->withPivot('id')->withTimestamps();
    }

    public function getRoles()
    {
        $roles = $this->roles()->get();
        $r = [];
        foreach($roles as $role) {
            $r[] = $role->slug;
        }
        return $r;
    }

    public function hasRole($role)
    {
        return (bool) $this->roles()->where(function ($query) use ($role) {
            $query->where('roles.slug', $role)
                ->orWhere('roles.slug', 'root');
        })->first();
    }

    public function teacher()
    {
        return $this->hasOne('App\Models\Teacher');
    }

    public function student()
    {
        return $this->hasOne('App\Models\Student');
    }

    public function socialnetworks()
    {
        return $this->hasMany(\App\Models\SocialNetwork::class);
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ticket');
    }
}