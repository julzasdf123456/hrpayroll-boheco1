<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRankingRepository extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RankingRepository', function (Blueprint $table) {
            $table->id();
            $table->string('Type', 2000)->nullable();
            $table->string('RankingName', 1000)->nullable();
            $table->string('Description', 1000)->nullable();
            $table->string('Points', 10)->nullable();
            $table->string('Notes', 1000)->nullable();
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
        Schema::dropIfExists('RankingRepository');
    }
}
