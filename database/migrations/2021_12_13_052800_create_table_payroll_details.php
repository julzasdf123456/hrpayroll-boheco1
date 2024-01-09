<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayrollDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PayrollDetails', function (Blueprint $table) {
            $table->id();
            $table->string('PayrolIndexId')->nullable();
            $table->string('EmployeeId')->nullable();
            $table->string('GrossSalary')->nullable();
            $table->string('TotalDeductions')->nullable();
            $table->string('AddOns')->nullable();
            $table->string('Vat')->nullable();
            $table->string('NetSalary')->nullable();
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
        Schema::dropIfExists('PayrollDetails');
    }
}
