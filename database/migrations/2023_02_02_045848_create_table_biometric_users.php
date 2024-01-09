<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBiometricUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BiometricUsers', function (Blueprint $table) {
            $table->string('id', 100)->unsigned();
            $table->primary('id');
            $table->string('UID')->nullable();
            $table->string('Name', 250)->nullable();
            $table->string('UserId')->nullable();
            $table->string('Role')->nullable();
            $table->string('Notes', 500)->nullable();
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
        Schema::dropIfExists('BiometricUsers');
    }
}
