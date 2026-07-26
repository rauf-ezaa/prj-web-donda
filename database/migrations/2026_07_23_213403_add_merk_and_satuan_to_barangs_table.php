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
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('merk_spesifikasi')->nullable()->after('nama_barang');
            $table->enum('satuan', [
                'DUS', 'KG', 'BOTOL', 'BOX', 'BUAH', 'BUNGKUS',
                'UNIT', 'PACK', 'PCS', 'BUKU', 'RIM', 'PAD',
            ])->default('BUAH')->after('merk_spesifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['merk_spesifikasi', 'satuan']);
        });
    }
};
