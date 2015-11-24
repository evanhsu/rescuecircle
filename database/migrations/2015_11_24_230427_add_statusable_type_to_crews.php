<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusableTypeToCrews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crews', function (Blueprint $table) {
            $table->string("statusable_type",30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crews', function (Blueprint $table) {
            $table->dropColumn("statusable_type");
        });
    }
}
