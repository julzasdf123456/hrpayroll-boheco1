<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayrollIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PayrollIndex', function (Blueprint $table) {
            $table->id();
            $table->date('DateFrom')->nullable();
            $table->date('DateTo')->nullable();
            $table->date('SalaryPeriod')->nullable();
            $table->string('EmployeeType')->nullable(); // Contractual, Provational, Regular
            $table->string('Notes')->nullable();
            $table->string('Department')->nullable();
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
        Schema::dropIfExists('PayrollIndex');
    }
}
