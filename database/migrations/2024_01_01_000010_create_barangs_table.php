<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 100);
            $table->string('merk_spesifikasi')->nullable();
            $table->enum('satuan', [
                'DUS', 'KG', 'BOTOL', 'BOX', 'BUAH', 'BUNGKUS',
                'UNIT', 'PACK', 'PCS', 'BUKU', 'RIM', 'PAD',
            ])->default('BUAH');
            $table->double('harga_barang');
            $table->integer('stok_tersedia')->default(0);
            $table->text('description');
            $table->foreignId('klasifikasi_kib')->constrained('kib')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
