<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_id')->unsigned()->index();
            $table->string('content');
            $table->tinyInteger('type')->comment('Text, checkbox, checkbox-unchecked');
            $table->foreign('note_id')->references('id')->on('notes');
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
        Schema::dropIfExists('note_contents');
    }
}
