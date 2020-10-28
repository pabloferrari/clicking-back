<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'group_enabled'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class);
    }
}
