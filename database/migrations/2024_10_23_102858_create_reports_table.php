<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('post_models')->onDelete('cascade'); // เปลี่ยนจาก posts เป็น post_models
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->enum('reason', [
                'inappropriate_content', 'inappropriate_image_video', 'copyright_infringement',
                'spam', 'scam', 'off_topic', 'privacy_violation', 'offensive_language', 'misinformation'
            ]);
            $table->text('additional_info')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}