<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAttendanceRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AttendanceRules', function (Blueprint $table) {
            $table->id();
            $table->time('MorningTimeInStart')->nullable();
            $table->time('MorningTimeInEnd')->nullable();
            $table->time('MorningAbsentThreshold')->nullable();
            $table->time('MorningTimeOutStart')->nullable();
            $table->time('MorningTimeOutEnd')->nullable();
            $table->time('MorningUndertimeThreshold')->nullable();
            $table->time('AfternoonTimeInStart')->nullable();
            $table->time('AfternoonTimeInEnd')->nullable();
            $table->time('AfternoonAbsentThreshold')->nullable();
            $table->time('AfternoonTimeOutStart')->nullable();
            $table->time('AfternoonTimeOutEnd')->nullable();
            $table->time('AfternoonUndertimeThreshold')->nullable();
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
        Schema::dropIfExists('AttendanceRules');
    }
}
