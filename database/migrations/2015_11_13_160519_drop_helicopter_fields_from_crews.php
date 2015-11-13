<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropHelicopterFieldsFromCrews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('crews', function ($table) {
            $table->dropColumn(['helicopter1_tailnumber', 'helicopter1_model', 'helicopter2_tailnumber', 'helicopter2_model']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration cannot be reversed because it destroys data.
    }
}
