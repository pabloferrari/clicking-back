<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $table = 'bbb_meetings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['meeting_request_id','meetingId','internalMeetingID','parentMeetingID','name','attendeePW','moderatorPW','createTime','voiceBridge','dialNumber','createDate','record_id','welcome','returncode','record','autoStartRecording','allowStartStopRecording','webcamsOnlyForModerator','muteOnStart','allowModsToUnmuteUsers','lockSettingsDisableCam','lockSettingsDisableMic','lockSettingsDisablePrivateChat','lockSettingsDisablePublicChat','lockSettingsDisableNote','lockSettingsLockedLayout','lockSettingsLockOnJoin','lockSettingsLockOnJoinConfigurable','logo','bannerText','bannerColor','webVoice','logoutUrl','maxParticipants','duration','meta','webVoiceConf','publish','redirect','clientUrl','configToken','avatarUrl','checksum'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function meetingRequest()
    {
        return $this->belongsTo(\App\Models\MeetingRequest::class);
    }

}
