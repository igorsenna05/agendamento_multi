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
        Schema::create('decision_tree', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('action_type'); // link, sub_option, schedule
            $table->string('action_value')->nullable(); //URL, null, etc.
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('decision_tree')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decision_tree');
    }
};
