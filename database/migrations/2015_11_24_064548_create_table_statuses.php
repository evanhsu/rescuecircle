<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('statusable_type',30); // NOT NULL
            $table->integer('statusable_id'); // NOT NULL

            $table->string('statusable_name',100);

            $table->double('latitude',11,8);
            $table->double('longitude',11,8);

            $table->string('staffing_category1',30)->nullable();
            $table->string('staffing_value1',30)->nullable();
            $table->string('staffing_category2',30)->nullable();
            $table->string('staffing_value2',30)->nullable();

            $table->string('manager_name',50)->nullable();
            $table->string('manager_phone',20)->nullable();

            $table->text('comments1')->nullable();
            $table->text('comments2')->nullable();

            $table->string('assigned_fire_name',50)->nullable();
            $table->string('assigned_fire_number',50)->nullable();
            $table->string('assigned_supervisor',50)->nullable();
            $table->string('assigned_supervisor_phone',20)->nullable();

            $table->string('created_by',50);

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
        Schema::drop('statuses');
    }
}
