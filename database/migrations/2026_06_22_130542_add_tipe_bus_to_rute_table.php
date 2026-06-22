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
        Schema::table('rute', function (Blueprint $table) {
            $table->enum('tipe_bus', ['Economy', 'Executive'])->default('Economy')->after('kota_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rute', function (Blueprint $table) {
            $table->dropColumn('tipe_bus');
        });
    }
};
