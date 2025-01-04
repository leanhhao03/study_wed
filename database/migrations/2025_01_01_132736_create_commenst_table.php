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
        Schema::create('commenst', function (Blueprint $table) {
            $table->id('Cm_id');
            $table->unsignedBigInteger('dcm_id');
            $table->unsignedBigInteger('us_id');
            $table->text('Cm_Content');
            $table->timestamps();

            //foreign key
            $table->foreign('dcm_id')->references('Dcm_id')->on('document');
            $table->foreign('us_id')->references('Us_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commenst');
    }
};
