<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BBBMeetingRequest extends Model
{
    use HasFactory;
    protected $table = 'bbb_meeting_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','institution_id','meeting_type','model','model_id','minutes','title','created'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function meeting()
    {
        return $this->hasOne(\App\Models\Meeting::class);
    }
}
