<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique(); // Misal: TKT-20260520-ABCDE
            $table->foreignId('jadwal_id')->constrained('jadwal')->onDelete('cascade');
            $table->foreignId('penumpang_id')->constrained('penumpang')->onDelete('cascade');
            $table->foreignId('kursi_id')->constrained('kursi')->onDelete('cascade');
            
            $table->integer('harga'); // Snapshot harga saat dipesan
            $table->enum('status_pembayaran', ['Unpaid', 'Pending', 'Paid', 'Failed'])->default('Unpaid');
            $table->enum('status_tiket', ['Aktif', 'Digunakan', 'Dibatalkan'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket');
    }
};