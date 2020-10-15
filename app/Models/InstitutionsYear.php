<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionsYear extends Model
{
    use HasFactory;

    protected $fillable = [
		'id',
        'year',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'institution_id'
    ];

    public function institution() {
        return $this->belongsTo(\App\Models\Institution::class);
    }

}
