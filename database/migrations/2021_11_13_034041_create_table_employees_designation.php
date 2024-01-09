<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployeesDesignation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('EmployeesDesignations', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeId', 100)->nullable();
            $table->string('PositionId', 100)->nullable();
            $table->string('Description', 2000)->nullable();
            $table->string('Status', 100)->nullable();
            $table->date('DateStarted')->nullable();
            $table->date('DateEnd')->nullable();
            $table->string('SalaryGrade', 200)->nullable();
            $table->string('SubjectLoad')->nullable();
            $table->string('SalaryPerLoad')->nullable();
            $table->string('SalaryAmount', 300)->nullable();
            $table->string('SalaryAddOns', 300)->nullable();
            $table->string('IsActive', 50)->nullable();
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
        Schema::dropIfExists('EmployeesDesignations');
    }
}
