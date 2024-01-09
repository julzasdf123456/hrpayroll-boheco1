<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAttendanceData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AttendanceData', function (Blueprint $table) {
            $table->string('id')->unsigned();
            $table->primary('id');
            $table->string('BiometricUserId')->nullable();
            $table->string('EmployeeId')->nullable();
            $table->string('UserId')->nullable();
            $table->datetime('Timestamp')->nullable();
            $table->string('State')->nullable();
            $table->string('UID')->nullable();
            $table->string('DeviceIp')->nullable();
            $table->string('AbsentPermission')->nullable();
            $table->string('Type')->nullable();
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
        Schema::dropIfExists('AttendanceData');
    }
}
