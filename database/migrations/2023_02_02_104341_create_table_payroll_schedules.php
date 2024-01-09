<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayrollSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PayrollSchedules', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Name', 300)->nullable();
            $table->time('StartTime')->nullable();
            $table->time('BreakStart')->nullable();
            $table->time('BreakEnd')->nullable();
            $table->time('EndTime')->nullable();
            $table->string('Notes', 1500)->nullable();
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
        Schema::dropIfExists('PayrollSchedules');
    }
}
