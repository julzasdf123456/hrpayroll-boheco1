<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRankings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rankings', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeId', 50)->nullable();
            $table->string('RankingRepositoryId', 50)->nullable();
            $table->string('Notes', 1000)->nullable();
            $table->string('Points')->nullable();
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
        Schema::dropIfExists('Rankings');
    }
}
