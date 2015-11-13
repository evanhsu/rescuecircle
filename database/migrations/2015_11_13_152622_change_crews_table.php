<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCrewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Define string lengths and make fields nullable
        Schema::table('crews', function($table) {

            $table->string('name', 100)->change();

            $table->string('address_street1', 100)->nullable()->change();
            $table->string('address_street2', 100)->nullable()->change();
            $table->string('address_city', 100)->nullable()->change();
            $table->string('address_state', 2)->nullable()->change();
            $table->string('zip', 10)->nullable()->change();

            $table->string('phone',20)->nullable()->change();
            $table->string('fax',20)->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove string length definitions and remove 'nullable' attribute
        Schema::table('crews', function($table) {

            $table->string('fax')->change();
            $table->string('phone')->change();

            $table->string('zip')->change();
            $table->string('address_state')->change();
            $table->string('address_city')->change();
            $table->string('address_street2')->change();
            $table->string('address_street1')->change();

            $table->string('name')->change();
        });
    }
}
