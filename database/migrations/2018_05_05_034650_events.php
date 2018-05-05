<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Events extends Migration
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
            $table->string('name');
            $table->string('address');
            $table->string('location');
            $table->string('lat');
            $table->string('lng');
            $table->string('description');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('thumbnail');
            $table->string('image');
            $table->string('rec_id')->unique();
            $table->string('reservations_required');
            $table->string('free');
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
        //
    }
}
