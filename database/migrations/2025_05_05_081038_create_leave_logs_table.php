<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('LeaveLogs', function (Blueprint $table) {
            $table->string('id')->primary()->default(DB::raw('NEWID()')); 
            $table->string('UserId', 255)->nullable(); 
            $table->string('LeaveType', 25)->nullable(); 
            $table->float('PreviousBalance')->nullable(); 
            $table->float('NowBalance')->nullable();
            $table->float('GapFromBalances')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LeaveLogs');
    }
};
