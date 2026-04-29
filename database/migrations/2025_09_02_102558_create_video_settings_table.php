<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('video_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Judul video
            $table->text('video_path')->nullable(); // Untuk video lokal (path file)
            $table->text('embed_url')->nullable(); // Untuk embed (YouTube/Vimeo)
            $table->boolean('is_active')->default(true); // Aktif/tidak
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_settings');
    }
};