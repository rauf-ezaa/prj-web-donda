<?php
// PERHATIAN: Tabel ini (permintaan_details, dengan akhiran 's') tampak sebagai
// DUPLIKAT dari tabel 'permintaan_detail' (tanpa akhiran 's') pada migration
// sebelumnya. Struktur kolomnya identik. Ini kemungkinan bug/sisa refactor lama
// (mis. rename tabel yang tidak tuntas). Disarankan untuk mengecek Model mana
// yang benar-benar dipakai (permintaan_detail vs permintaan_details) lalu
// menghapus salah satunya. File ini tetap dibuat agar migrate:fresh sesuai
// DDL asli tidak error, tapi sebaiknya di-review sebelum production.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaans')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->unsignedInteger('jumlah_diminta');
            $table->unsignedInteger('jumlah_disetujui')->nullable();
            $table->text('catatan_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_details');
    }
};
