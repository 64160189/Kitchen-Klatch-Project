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
        Schema::table('post_models', function (Blueprint $table) {
            $table->integer('time_to_cook')->nullable(); // Time in minutes
            $table->tinyInteger('level_of_cook')->default(1); // Default to 'ง่ายมาก'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_models', function (Blueprint $table) {
            $table->dropColumn('time_to_cook');
            $table->dropColumn('level_of_cook');
        });
    }
};
