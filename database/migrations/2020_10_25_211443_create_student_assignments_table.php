<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id')->unsigned()->index();
            $table->unsignedBigInteger('classroom_student_id')->unsigned()->index();
            $table->foreign('assignment_id')->references('id')->on('assignments');
            $table->foreign('classroom_student_id')->references('id')->on('classroom_students');
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
        Schema::dropIfExists('student_assignments');
    }
}