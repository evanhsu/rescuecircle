<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullValuesForStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->string('staffing_category1',30)->nullable()->change();
            $table->string('staffing_value1',30)->nullable()->change();
            $table->string('staffing_category2',30)->nullable()->change();
            $table->string('staffing_value2',30)->nullable()->change();

            $table->string('manager_name',50)->nullable()->change();
            $table->string('manager_phone',20)->nullable()->change();

            $table->text('comments1')->nullable()->change();
            $table->text('comments2')->nullable()->change();

            $table->string('assigned_fire_name',50)->nullable()->change();
            $table->string('assigned_fire_number',50)->nullable()->change();
            $table->string('assigned_supervisor',50)->nullable()->change();
            $table->string('assigned_supervisor_phone',20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->double('latitude',11,8)->change();
            $table->double('longitude',11,8)->change();

            $table->string('staffing_category1',30)->change();
            $table->string('staffing_value1',30)->change();
            $table->string('staffing_category2',30)->change();
            $table->string('staffing_value2',30)->change();

            $table->string('manager_name',50)->change();
            $table->string('manager_phone',20)->change();

            $table->text('comments1')->change();
            $table->text('comments2')->change();

            $table->string('assigned_fire_name',50)->change();
            $table->string('assigned_fire_number',50)->change();
            $table->string('assigned_supervisor',50)->change();
            $table->string('assigned_supervisor_phone',20)->change();
        });
    }
}
