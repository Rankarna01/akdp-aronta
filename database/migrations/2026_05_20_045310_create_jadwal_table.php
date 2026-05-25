<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rute')->onDelete('cascade');
            $table->foreignId('armada_id')->constrained('armada')->onDelete('cascade');
            $table->foreignId('supir_id')->constrained('supir')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->time('waktu_berangkat');
            $table->time('waktu_tiba')->nullable();
            $table->integer('harga_tiket'); // Bisa beda dengan harga dasar rute (misal tuslah hari raya)
            $table->enum('status', ['Menunggu', 'Berangkat', 'Selesai', 'Dibatalkan'])->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};