<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('remote_path')->nullable();
            $table->unsignedBigInteger('course_id')->unsigned()->index();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->unsignedBigInteger('institution_id')->unsigned()->index();
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
        Schema::dropIfExists('folders');
    }
}
