<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->unsignedInteger('qty_pinjam');
            $table->unsignedInteger('qty_kembali_baik')->default(0);
            $table->unsignedInteger('qty_kembali_rusak_ringan')->default(0);
            $table->unsignedInteger('qty_kembali_rusak_berat')->default(0);
            $table->unsignedInteger('qty_kembali_hilang')->default(0);
            $table->unsignedInteger('qty_kembali_habis_terpakai')->default(0);
            $table->enum('status', ['dipinjam', 'sebagian', 'selesai'])->default('dipinjam');
            $table->timestamps();
            $table->bigInteger('peminjaman_item_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_items');
    }
};
