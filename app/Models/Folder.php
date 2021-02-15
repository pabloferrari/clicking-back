<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'remote_path',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
