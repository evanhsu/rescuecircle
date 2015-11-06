<?php

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
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->integer('crew_id');
            $table->string('encrypted_password', 60);
            $table->string('salt', 60);
            $table->tinyInteger('global_admin');    // 0 or 1 (Trouble with the BOOLEAN data type when migrating between different dB's)
            $table->rememberToken();
            $table->timestamps();   # created_at AND updated_at
            $table->dateTime('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
