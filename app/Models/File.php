<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model_name',
        'model_id',
        'path',
        'remote_path',
        'url',
        'migrated',
        'user_id',
        'status',
        'size'
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function news()
    {
        return $this->belongsTo(\App\Models\News::class, 'id');
    }
}