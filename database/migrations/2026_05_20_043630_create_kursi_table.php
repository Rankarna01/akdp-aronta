<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel tunggal 'kursi' tanpa akhiran s
        Schema::create('kursi', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel armada (cascade: jika armada dihapus, data kursi ikut terhapus)
            $table->foreignId('armada_id')->constrained('armada')->onDelete('cascade');
            $table->string('nomor_kursi', 10);
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();

            // Proteksi double input nomor kursi pada satu bus yang sama
            $table->unique(['armada_id', 'nomor_kursi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kursi');
    }
};