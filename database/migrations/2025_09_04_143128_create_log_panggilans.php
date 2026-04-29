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
        Schema::create('log_panggilans', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_antrian');
        $table->unsignedBigInteger('unit_id');
        $table->unsignedBigInteger('loket_id')->nullable();
        $table->timestamp('dipanggil_at');
        $table->timestamp('diproses_at')->nullable();
        $table->enum('status', ['waiting', 'called', 'done', 'skipped']);
        $table->timestamps();

        $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        $table->foreign('loket_id')->references('id')->on('lokets')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('log_panggilans');
    }
};
