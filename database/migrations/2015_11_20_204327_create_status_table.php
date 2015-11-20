<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration
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
            $table->string('statusable_id',10); // NOT NULL

            $table->string('statusable_name',100);

            $table->double('latitude',11,8);
            $table->double('longitude',11,8);

            $table->string('staffing_category1',30);
            $table->string('staffing_value1',30);
            $table->string('staffing_category2',30);
            $table->string('staffing_value2',30);

            $table->string('manager_name',50);
            $table->string('manager_phone',20);

            $table->text('comments1');
            $table->text('comments2');

            $table->string('assigned_fire_name',50);
            $table->string('assigned_fire_number',50);
            $table->string('assigned_supervisor',50);
            $table->string('assigned_supervisor_phone',20);

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
