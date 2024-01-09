<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLeaveApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LeaveApplications', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeId')->nullable();
            $table->date('DateFrom')->nullable();
            $table->date('DateTo')->nullable();
            $table->integer('NumberOfDays')->nullable();
            $table->string('Content', 2000)->nullable();
            $table->string('Status')->nullable();
            $table->string('LeaveType', 100)->nullable();
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
        Schema::dropIfExists('LeaveApplications');
    }
}
