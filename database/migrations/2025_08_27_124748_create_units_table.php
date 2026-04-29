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
       Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit', 100);       // Nama unit pelayanan
            $table->string('kode_unit', 10);   // Kode unit unik
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif'); // ENUM dengan default 'aktif'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
