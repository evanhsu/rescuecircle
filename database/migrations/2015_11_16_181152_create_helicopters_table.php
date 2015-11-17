<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelicoptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helicopters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tailnumber', 6)->unique();
            $table->string('model', 50)->nullable();
            $table->integer('crew_id')->nullable();
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
        Schema::drop('helicopters');
    }
}
