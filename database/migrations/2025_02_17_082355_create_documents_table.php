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
        Schema::create('documents', function (Blueprint $table) {
            $table->id('Dcm_id');
            $table->string('dcm_title');
            $table->text('dcm_description');
            $table->text('dcm_file_path');
            $table->string('dcm_preview_path')->nullable();
            $table->unsignedBigInteger('us_id');
            $table->timestamps();

            //foreign key
            $table->foreign('us_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
