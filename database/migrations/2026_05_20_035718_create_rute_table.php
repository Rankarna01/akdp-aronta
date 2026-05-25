<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gunakan nama tabel 'rute' (tanpa s)
        Schema::create('rute', function (Blueprint $table) {
            $table->id();
            $table->string('kota_asal');
            $table->string('kota_tujuan');
            $table->integer('jarak_km')->nullable();
            $table->string('estimasi_waktu')->nullable(); // contoh: "12 Jam 30 Menit"
            $table->bigInteger('harga_dasar'); // Harga standar tiket
            $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rute');
    }
};