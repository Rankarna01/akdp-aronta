<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama tabel diset 'armada' (tanpa s)
        Schema::create('armada', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bus');
            $table->string('plat_nomor')->unique();
            $table->enum('tipe_bus', ['Ekonomi', 'Bisnis', 'Executive', 'Royal Class']);
            $table->integer('total_kursi');
            $table->enum('status', ['Aktif', 'Maintenance', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armada');
    }
};