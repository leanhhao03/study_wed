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
        Schema::create('user', function (Blueprint $table) {
            $table->id('Us_id');
            $table->string('us_name');
            $table->string('us_email')->unique();
            $table->string('us_password');
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('us_role', ['student', 'admin']) -> default('student');
            $table-> enum('us_gender', ['male', 'female', 'other'])->nullable();
            $table->date('us_date_of_birth')->nullable();
            $table->binary('us_pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
