<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('turn_id')->unsigned()->index();
            $table->unsignedBigInteger('institution_year_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('turn_id')->references('id')->on('turns');
            $table->foreign('institution_year_id')->references('id')->on('institutions_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commissions');
    }
}
