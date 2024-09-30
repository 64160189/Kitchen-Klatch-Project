<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('notifiable_type')->nullable(); // เพิ่มคอลัมน์ notifiable_type
            $table->unsignedBigInteger('notifiable_id')->nullable(); // เพิ่มคอลัมน์ notifiable_id
            $table->text('data')->nullable(); // เพิ่มคอลัมน์ data
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('notifiable_type');
            $table->dropColumn('notifiable_id');
            $table->dropColumn('data');
        });
    }

};
