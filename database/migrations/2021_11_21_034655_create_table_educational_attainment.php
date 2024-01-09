<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEducationalAttainment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('EducationalAttainment', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeId')->nullable();
            $table->string('Type')->nullable();
            $table->string('Major', 500)->nullable();
            $table->string('School', 1000)->nullable();
            $table->string('SchoolYear', 60)->nullable();
            $table->string('Certification', 2000)->nullable();
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
        Schema::dropIfExists('EducationalAttainment');
    }
}
