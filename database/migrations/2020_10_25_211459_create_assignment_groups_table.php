<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classroom_student_id')->unsigned()->index();
            $table->unsignedBigInteger('assignment_id')->unsigned()->index();
            $table->integer('num')->default(0);
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
        Schema::dropIfExists('assignment_groups');
    }
}
