<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->foreignId('dikembalikan_oleh')->constrained('users');
            $table->dateTime('tanggal_pengembalian');
            $table->text('catatan')->nullable();
            $table->enum('status', [
                'menunggu_verifikasi_admin', 'menunggu_verifikasi_spv',
                'selesai', 'ditolak_admin', 'ditolak_spv',
            ])->default('menunggu_verifikasi_admin');
            $table->foreignId('diverifikasi_admin_oleh')->nullable()->constrained('users');
            $table->dateTime('diverifikasi_admin_at')->nullable();
            $table->foreignId('diverifikasi_spv_oleh')->nullable()->constrained('users');
            $table->dateTime('diverifikasi_spv_at')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
    }
};
