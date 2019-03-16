<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 127);
            $table->date('beginning');
            $table->date('end');
            $table->string('place', 31);
            $table->dateTime('soft_deadline');
            $table->dateTime('hard_deadline');
            $table->unsignedInteger('organizer');
            $table->text('note')->nullable();
            $table->foreign('organizer')->references('id')->on('users')->onDelete('restrict');
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
        Schema::dropIfExists('events');
    }
}
