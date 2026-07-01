<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('no_ktp', 20)->unique();
            $table->string('no_sim', 20)->unique();
            $table->string('no_hp', 15);
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable(); 
            $table->enum('status', ['Aktif', 'Cuti', 'Non-Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supir');
    }
};