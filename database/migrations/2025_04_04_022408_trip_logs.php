<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('TripTicketLogs', function (Blueprint $table) {
            $table->uuid('id');
            $table->string("TripTicketId")->nullable();
            $table->string("GuardId")->nullable();
            $table->string("Status")->nullable(); // "DEPARTED" | "ARRIVED"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TripTicketLogs');
    }
};
