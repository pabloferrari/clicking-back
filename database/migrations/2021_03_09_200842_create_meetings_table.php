<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned()->index()->comment('The user who creates the meeting');
            $table->unsignedBigInteger('institution_id')->unsigned();
            $table->string('link');
            $table->string('model')->comment('Table: classroom, classe, teacher, user')->nullable();
            $table->integer('model_id')->default(0);
            $table->json('ids')->nullable();
            $table->integer('minutes')->default(30);
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('meetings');
    }
}
