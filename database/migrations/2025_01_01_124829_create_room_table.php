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
        Schema::create('room_video', function (Blueprint $table) {
            $table->id('Rv_id');
            $table->string('Rv_name');
            $table->unsignedBigInteger('Rv_host_user');
            $table->timestamps();

            //foreign key
            $table->foreign('Rv_host_user')->references('Us_id')->on('user');
        });

        Schema::create('member_room', function (Blueprint $table){
            $table->id('Mr_id');
            $table->unsignedBigInteger('Rv_id');
            $table->unsignedBigInteger('us_id');
            $table->timestamp('joined_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('Rv_id')->references('Rv_id')->on('room_video');
            $table->foreign('us_id')->references('Us_id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_video');
        Schema::dropIfExists('member_room');
    }
};
