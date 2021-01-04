<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBbbMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_request_id')->unsigned()->index();
            $table->string('meetingId');
            $table->string('internalMeetingID')->nullable();
            $table->string('parentMeetingID')->nullable();
            $table->string('name');
            $table->string('attendeePW');
            $table->string('moderatorPW');
            $table->string('createTime')->nullable();
            $table->integer('voiceBridge')->default(0);
            $table->string('dialNumber')->nullable();
            $table->string('createDate')->nullable();
            
            // $table->string('record_id');
            $table->string('welcome')->default('Bienvenido');;
            $table->string('returncode')->nullable();
            $table->boolean('record')->default(true);
            $table->boolean('autoStartRecording')->default(true);
            $table->boolean('allowStartStopRecording')->default(false);
            $table->boolean('webcamsOnlyForModerator')->default(false);
            $table->boolean('muteOnStart')->default(false);
            $table->boolean('allowModsToUnmuteUsers')->default(false);
            $table->boolean('lockSettingsDisableCam')->default(false);
            $table->boolean('lockSettingsDisableMic')->default(false);
            $table->boolean('lockSettingsDisablePrivateChat')->default(false);
            $table->boolean('lockSettingsDisablePublicChat')->default(false);
            $table->boolean('lockSettingsDisableNote')->default(false);
            $table->boolean('lockSettingsLockedLayout')->default(false);
            $table->boolean('lockSettingsLockOnJoin')->default(true);
            $table->boolean('lockSettingsLockOnJoinConfigurable')->default(false);
                        
            $table->string('logo')->nullable();
            $table->string('bannerText')->nullable();
            $table->string('bannerColor')->nullable();
            

            
            $table->string('webVoice')->nullable();
            $table->string('logoutUrl')->nullable();
            $table->integer('maxParticipants')->nullable();
            $table->integer('duration')->nullable();
            $table->string('meta')->nullable();
            $table->string('webVoiceConf')->nullable();
            $table->string('publish')->nullable();
            $table->string('redirect')->nullable();
            $table->string('clientUrl')->nullable();
            $table->string('configToken')->nullable();
            $table->string('avatarUrl')->nullable();
            $table->string('checksum')->nullable();
            

            $table->foreign('meeting_request_id')->references('id')->on('bbb_meeting_requests');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bbb_meetings');
    }
}