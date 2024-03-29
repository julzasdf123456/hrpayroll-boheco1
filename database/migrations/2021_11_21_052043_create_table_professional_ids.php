<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProfessionalIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ProfessionalIDs', function (Blueprint $table) {
            $table->id();
            $table->string('EmployeeId')->nullable();
            $table->string('Entity', 100)->nullable();
            $table->string('EntityId')->nullable();
            $table->string('ContributionAmount')->nullable();
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
        Schema::dropIfExists('ProfessionalIDs');
    }
}
