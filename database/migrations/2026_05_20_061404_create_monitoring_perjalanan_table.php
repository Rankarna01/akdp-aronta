<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel tanpa 's'
        Schema::create('monitoring_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->string('lokasi_sekarang');
            $table->enum('status', ['Persiapan', 'Di Perjalanan', 'Istirahat', 'Kendala', 'Tiba'])->default('Di Perjalanan');
            $table->text('keterangan')->nullable();
            $table->timestamps(); // create_at akan menjadi penanda waktu update lokasi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_perjalanan');
    }
};