<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->string('nama_supplier');
            $table->date('tanggal_diterima');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->enum('status', ['menunggu_verifikasi_spv', 'selesai', 'ditolak'])
                ->default('menunggu_verifikasi_spv');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users');
            $table->dateTime('diverifikasi_at')->nullable();
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
