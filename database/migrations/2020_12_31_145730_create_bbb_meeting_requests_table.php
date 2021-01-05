<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBbbMeetingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_meeting_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned()->index()->comment('The user who creates the meeting');
            $table->unsignedBigInteger('institution_id')->unsigned();
            $table->unsignedBigInteger('meeting_type')->unsigned();
            
            $table->string('model')->comment('Table: classroom, classe, teacher, user');
            $table->integer('model_id')->default(0);
            $table->json('ids')->nullable();
            
            $table->integer('minutes')->default(30);

            $table->string('title')->nullable();
            $table->boolean('created')->default(false);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('meeting_type')->references('id')->on('bbb_meeting_types');
            $table->foreign('institution_id')->references('id')->on('institutions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bbb_meeting_requests');
    }
}