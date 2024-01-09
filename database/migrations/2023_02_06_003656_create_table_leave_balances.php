<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLeaveBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LeaveBalances', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('EmployeeId')->nullable();
            $table->decimal('Vacation', 15, 2)->nullable();
            $table->decimal('Sick', 15, 2)->nullable();
            $table->decimal('Special', 15, 2)->nullable();
            $table->decimal('Maternity', 15, 2)->nullable();
            $table->decimal('MaternityForSoloMother', 15, 2)->nullable();
            $table->decimal('Paternity', 15, 2)->nullable();
            $table->decimal('SoloParent', 15, 2)->nullable();
            $table->string('Notes', 300)->nullable();
            $table->string('Month')->nullable();
            $table->string('Year')->nullable();
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
        Schema::dropIfExists('LeaveBalances');
    }
}
