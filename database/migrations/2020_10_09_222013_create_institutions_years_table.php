<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions_years', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->unsignedBigInteger('institution_id')->unsigned()->index();
            $table->foreign('institution_id')->references('id')->on('institutions');
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
        Schema::dropIfExists('institutions_years');
    }
}
