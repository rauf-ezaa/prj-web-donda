<?php
// PERBAIKAN UTAMA: Pada DDL asli, kolom 'barang_id' di tabel 'persedians'
// TIDAK memiliki foreign key constraint sama sekali (hanya kolom polos tanpa
// index/constrained). Ini sangat mungkin adalah migration yang hilang/rusak
// yang menyebabkan error "constrained-nya hilang" seperti yang Anda alami.
// Di bawah ini saya tambahkan foreignId()->constrained('barangs') yang benar.
// Jika Anda TIDAK ingin FK ditambahkan (agar 100% sama seperti DDL lama),
// ganti baris foreignId(...) menjadi:
//   $table->unsignedBigInteger('barang_id');

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persedians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->enum('asal_dana', ['bos', 'bop']);
            $table->integer('qty');
            $table->date('tanggal_masuk');
            $table->double('harga_satuan_unit')->default(123.4567);
            $table->timestamps();
            $table->double('harga_total');
            $table->enum('approval_status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_approval')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persedians');
    }
};
