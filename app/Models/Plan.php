<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function institutions() {
        return $this->hasMany(\App\Models\Institution::class);
    }
}
