<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Positions', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('Position', 450);
            $table->string('Description', 600)->nullable();
            $table->string('Level')->nullable();
            $table->string('ParentPositionId')->nullable();
            $table->string('Notes')->nullable();
            $table->number('BasicSalary')->nullable();
            $table->string('Department', 100)->nullable();
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
        Schema::dropIfExists('Positions');
    }
}
