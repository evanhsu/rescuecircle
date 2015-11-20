<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeHelicopterPrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helicopters', function (Blueprint $table) {
            
            // Drop the existing primary key from the 'id' column
            $table->dropPrimary('helicopters_id_primary');

            // Add a primary key to the 'tailnumber' field
            $table->primary('tailnumber');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helicopters', function (Blueprint $table) {
            
            // Drop the existing primary key from the 'tailnumber' column
            $table->dropPrimary('helicopters_tailnumber_primary');

            // Add a primary key to the 'id' field
            $table->primary('id');
        });
    }
}
