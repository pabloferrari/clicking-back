<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class NoteContent extends Model
{
    use HasFactory;

    protected $table = 'note_contents';

    protected $fillable = [
        'id',
        'note_id',
        'content',
        'type'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'note_id'
    ];

    public function note()
    {
        return $this->belongsTo(\App\Models\Node::class, 'note_id');
    }
}
