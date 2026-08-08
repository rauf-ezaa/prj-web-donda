<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            // Catatan: di DDL asli, barang_id HANYA punya index, tidak ada FK constraint
            // (kemungkinan barang bisa diajukan meski belum terdaftar di master barangs).
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->index('barang_id');
            $table->string('nama_barang_diajukan');
            $table->unsignedInteger('jumlah_diajukan');
            $table->text('catatan_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_detail');
    }
};
