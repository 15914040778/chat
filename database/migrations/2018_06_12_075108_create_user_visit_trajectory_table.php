<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVisitTrajectoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_visit_trajectory', function (Blueprint $table) {
            $table->increments('id');
            //user id
            $table->integer('user_id');
            //date
            $table->dateTime('date');
            //visit url
            $table->string('visit_url');
            //visit duration
            $table->integer('visit_duration');
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
        Schema::dropIfExists('user_visit_trajectory');
    }
}
