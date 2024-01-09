<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOvertimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Overtimes', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeId');
            $table->date('DateOfOT')->nullable();
            $table->time('From')->nullable();
            $table->time('To')->nullable();
            $table->string('Multiplier')->nullable();
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
        Schema::dropIfExists('Overtimes');
    }
}
