<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Employees', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('FirstName', 400)->nullable();
            $table->string('MiddleName', 400)->nullable();
            $table->string('LastName', 400)->nullable();
            $table->string('Suffix', 15)->nullable();
            $table->string('Gender', 10)->nullable();
            $table->date('Birthdate')->nullable();
            $table->string('StreetCurrent', 1000)->nullable();
            $table->string('BarangayCurrent', 400)->nullable();
            $table->string('TownCurrent', 400)->nullable();
            $table->string('ProvinceCurrent', 400)->nullable();
            $table->string('StreetPermanent', 1000)->nullable();
            $table->string('BarangayPermanent', 400)->nullable();
            $table->string('TownPermanent', 400)->nullable();
            $table->string('ProvincePermanent', 400)->nullable();
            $table->string('ContactNumbers', 500)->nullable();
            $table->string('EmailAddress', 500)->nullable();
            $table->string('BloodType', 10)->nullable();
            $table->string('CivilStatus', 100)->nullable();
            $table->string('Religion', 200)->nullable();
            $table->string('Citizenship', 100)->nullable();
            $table->string('Designation', 300)->nullable();
            $table->string('BiometricsUserId', 225)->nullable();
            $table->string('PayrollScheduleId')->nullable();
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
        Schema::dropIfExists('Employees');
    }
}
