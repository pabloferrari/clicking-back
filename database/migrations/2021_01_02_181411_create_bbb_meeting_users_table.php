<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBbbMeetingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_meeting_users', function (Blueprint $table) {
            $table->id();
            // REQUEST DATA
            $table->unsignedBigInteger('user_id')->unsigned()->index();
            $table->unsignedBigInteger('meeting_id')->unsigned()->index()->comment('Meeting id bbb_meetings table');
            $table->string('clicking_token');
            $table->string('meetingID');
            $table->string('password');
            $table->enum('type', ['attendee', 'moderator']);
            $table->tinyInteger('status')->default(0)->comment('0 Pending; 1 Success; 2 Error; 3 Finished');
            
            // RESPONSE DATA
            $table->string('internalMeetingID')->nullable();
            $table->string('userId')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('session_token')->nullable();
            $table->string('guestStatus')->nullable();
            $table->string('returncode')->nullable();
            
            $table->string('url')->nullable();


            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('meeting_id')->references('id')->on('bbb_meetings');

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
        Schema::dropIfExists('bbb_meeting_users');
    }
}
