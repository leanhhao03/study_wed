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
        Schema::create('document', function (Blueprint $table) {
            $table->id('Dcm_id');
            $table->string('dcm_title');
            $table->string('dcm_description');
            $table->string('dcm_file_path');
            $table->unsignedBigInteger('us_id');
            $table->timestamps();

            //foreign key
            $table->foreign('us_id')->references('Us_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};
