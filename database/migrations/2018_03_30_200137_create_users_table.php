<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            /*
             * temporary workaround (nullable)
             */
            $table->unsignedInteger('person_id')->nullable();
            $table->string('username', 63)->unique();
            $table->string('password');
            $table->string('api_token')->nullable();
            /*
             * temporary workaround (no connection to clovek table)
             */
            //$table->foreign('person_id')->references('clovek_ID')->on('clovek')->onDelete('restrict');
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
        Schema::dropIfExists('users');
    }
}
