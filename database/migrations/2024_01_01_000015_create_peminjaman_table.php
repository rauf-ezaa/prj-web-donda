<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('verified_by_admin')->nullable()->constrained('users');
            $table->timestamp('verified_at_admin')->nullable();
            $table->text('keperluan');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_wajib_kembali');
            $table->enum('status', [
                'draft', 'pending', 'menunggu_spv', 'approved', 'rejected',
                'dipinjam', 'sebagian_dikembalikan', 'menunggu_konfirmasi_kembali',
                'dikembalikan', 'selesai', 'dibatalkan',
            ])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamp('dikembalikan_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
