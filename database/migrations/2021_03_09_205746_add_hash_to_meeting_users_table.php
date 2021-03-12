<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHashToMeetingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meeting_users', function (Blueprint $table) {
            $table->boolean('joined')->default(false)->after('meeting_id');
            $table->string('hash')->nullable()->after('joined');
            $table->string('public_url')->nullable()->after('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_users', function (Blueprint $table) {
            //
        });
    }
}
