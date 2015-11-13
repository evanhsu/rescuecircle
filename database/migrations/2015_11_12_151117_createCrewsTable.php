<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            
            $table->string('address_street1');
            $table->string('address_street2');
            $table->string('address_city');
            $table->string('address_state');
            $table->string('zip');

            $table->string('phone');
            $table->string('fax');

            $table->string('helicopter1_tailnumber');
            $table->string('helicopter1_model');

            $table->string('helicopter2_tailnumber');
            $table->string('helicopter2_model');
            
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
        Schema::drop('crews');
    }
}
