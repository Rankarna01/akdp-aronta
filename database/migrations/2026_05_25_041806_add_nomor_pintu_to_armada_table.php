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
    Schema::table('armada', function (Blueprint $table) {
        $table->string('nomor_pintu', 10)->after('nama_bus')->nullable();
    });
}

public function down(): void
{
    Schema::table('armada', function (Blueprint $table) {
        $table->dropColumn('nomor_pintu');
    });
}
};
