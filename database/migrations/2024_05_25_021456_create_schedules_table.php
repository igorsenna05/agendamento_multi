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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_cpf');
            $table->string('user_insc');
            $table->unsignedBigInteger('slot_id');
            $table->string('service_type');
            $table->unsignedBigInteger('location_id');
            $table->string('confirmation_token');
            $table->timestamps();

            $table->foreign('slot_id')->references('id')->on('schedule_slots')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
