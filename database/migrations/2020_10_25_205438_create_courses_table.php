<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->unsigned()->index();
            $table->unsignedBigInteger('teacher_id')->unsigned()->index();
            $table->unsignedBigInteger('classroom_id')->unsigned()->index();
            $table->unsignedBigInteger('course_type_id')->unsigned()->index();

            
            
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('classroom_id')->references('id')->on('classrooms');
            $table->foreign('course_type_id')->references('id')->on('course_types');


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
        Schema::dropIfExists('courses');
    }
}
