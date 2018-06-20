<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_users', function (Blueprint $table) {
            $table->increments('id');
            //room id
            $table->integer('room_id');
            //user id
            $table->integer('user_id');
            //user get info room date
            $table->dateTime('date');
            //user grade
            $table->integer('user_grade');
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
        Schema::dropIfExists('room_users');
    }
}
