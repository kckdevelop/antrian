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
         Schema::create('panggilans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');   // relasi ke tabel units
            $table->unsignedBigInteger('loket_id')->nullable();  // relasi ke tabel lokets
            $table->string('nomor_antrian', 10);     // contoh: A001, B102
            $table->enum('status', ['waiting', 'called', 'done', 'skipped'])
                  ->default('waiting');              // status panggilan
            $table->timestamp('dipanggil_at')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('loket_id')->references('id')->on('lokets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panggilans');
    }
};
