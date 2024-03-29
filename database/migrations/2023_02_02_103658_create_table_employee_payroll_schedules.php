<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployeePayrollSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('EmployeePayrollSchedules', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('EmployeeId')->nullable();
            $table->string('ScheduleId')->nullable();
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
        Schema::dropIfExists('EmployeePayrollSchedules');
    }
}
