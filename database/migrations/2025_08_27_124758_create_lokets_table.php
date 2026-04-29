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
        Schema::create('lokets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_loket', 100); // Nama loket, contoh: Loket 1
            $table->unsignedBigInteger('unit_id'); // Relasi ke tabel units
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif'); // ENUM dengan default 'aktif'
            $table->timestamps();

            // Foreign Key
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokets');
    }
};