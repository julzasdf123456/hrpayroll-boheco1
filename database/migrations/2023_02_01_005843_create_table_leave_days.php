<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLeaveDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LeaveDays', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('LeaveId');
            $table->date('LeaveDate')->nullable();
            $table->double('Longevity', 10, 2)->nullable();
            $table->string('Notes', 2000)->nullable();
            $table->string('Duration')->nullable();
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
        Schema::dropIfExists('LeaveDays');
    }
}
