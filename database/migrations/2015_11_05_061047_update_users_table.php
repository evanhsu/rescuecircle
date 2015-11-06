<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->integer('crew_id')->nullable()->change();
            $table->string('salt', 60)->nullable()->change();
            $table->integer('global_admin')->default(0)->change();    // 0 or 1 (Trouble with the BOOLEAN data type when migrating between different dB's)
            $table->rememberToken()->nullable()->change();
            $table->dateTime('last_login_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->integer('crew_id')->change();
            $table->string('salt', 60)->change();
            $table->tinyInteger('global_admin')->change();    // 0 or 1 (Trouble with the BOOLEAN data type when migrating between different dB's)
            $table->rememberToken()->change();
            $table->dateTime('last_login_at')->change();
        });
    }
}
