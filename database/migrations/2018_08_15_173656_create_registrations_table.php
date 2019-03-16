<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 31);
            $table->string('surname', 31);
            $table->date('birthdate', 31);
            $table->string('id_number', 31)->nullable();
            $table->string('street', 255);
            $table->string('city', 127);
            $table->string('zip', 7);
            $table->text('note')->nullable();
            $table->string('event', 31);
            $table->boolean('confirmed')->default(0);
            $table->unsignedInteger('team')->nullable();
            $table->unsignedInteger('registered_by');
            $table->foreign('team')->references('id')->on('teams')->onDelete('restrict');
            $table->foreign('registered_by')->references('id')->on('users')->onDelete('restrict');
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
        Schema::dropIfExists('registrations');
    }
}
