<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            // user_id dibuat nullable jika ada aktivitas sistem otomatis
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('modul'); // Contoh: "Armada", "Tiket", "Auth"
            $table->string('aksi');  // Contoh: "Create", "Update", "Delete", "Login"
            $table->text('keterangan'); // Detail aktivitas
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};