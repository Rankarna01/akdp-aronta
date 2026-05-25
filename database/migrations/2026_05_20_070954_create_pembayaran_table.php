<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tiket_id')->constrained('tiket')->onDelete('cascade');
            $table->string('metode_pembayaran'); // Misal: Transfer BCA, BRI, Tunai, DANA
            $table->integer('jumlah_bayar');
            $table->string('bukti_transfer')->nullable(); // Path foto bukti transfer
            $table->enum('status', ['Pending', 'Lunas', 'Ditolak'])->default('Pending');
            $table->timestamp('tanggal_bayar')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};