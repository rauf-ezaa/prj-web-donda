<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->unsignedInteger('jumlah_pinjam');
            $table->unsignedInteger('jumlah_disetujui')->nullable();
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable();
            $table->text('catatan_kembali')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_detail');
    }
};
