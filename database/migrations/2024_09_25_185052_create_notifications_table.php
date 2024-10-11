<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // อ้างอิงไปยัง users table
            $table->foreignId('post_id')->constrained('post_models')->onDelete('cascade'); // อ้างอิงไปยัง post_models table
            $table->string('message'); // ข้อความของการแจ้งเตือน
            $table->boolean('is_read')->default(false); // สถานะอ่าน
            $table->timestamps(); // created_at และ updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}

