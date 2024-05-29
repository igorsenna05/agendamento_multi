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
        Schema::table('decision_tree', function (Blueprint $table) {
            $table->foreignId('instruction_id')->nullable()->constrained('instructions')->onDelete('cascade');
            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decision_tree', function (Blueprint $table) {
            $table->dropForeign(['instruction_id']);
            $table->dropColumn('instruction_id');
            $table->dropColumn('position');
        });
    }
};
