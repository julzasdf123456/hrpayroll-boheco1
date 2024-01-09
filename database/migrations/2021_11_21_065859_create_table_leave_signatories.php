<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLeaveSignatories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LeaveSignatories', function (Blueprint $table) {
            $table->id();
            $table->string('LeaveId')->nullable();
            $table->string('EmployeeId')->nullable(); // Signatory
            $table->integer('Rank')->nullable(); // Heirarchy of signatory
            $table->string('Status')->nullable();
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
        Schema::dropIfExists('LeaveSignatories');
    }
}
