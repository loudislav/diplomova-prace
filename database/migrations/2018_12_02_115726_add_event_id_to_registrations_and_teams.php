<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventIdToRegistrationsAndTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function($table) {
            $table->unsignedInteger('event_id')->after('event')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
        });
        Schema::table('teams', function($table) {
            $table->unsignedInteger('event_id')->after('event')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrations', function($table) {
            $table->dropColumn('event_id');
        });
        Schema::table('teams', function($table) {
            $table->dropColumn('event_id');
        });
    }
}
