<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLeaveBalanceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LeaveBalanceDetails', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('EmployeeId')->nullable();
            $table->string('Method')->nullable(); // ADD, DEDUCT
            $table->decimal('Days', 15, 2)->nullable();
            $table->string('Details', 2000)->nullable();
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
        Schema::dropIfExists('LeaveBalanceDetails');
    }
}
