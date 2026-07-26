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
            $table->text('keperluan');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_wajib_kembali');
            $table->enum('status',
						[ 'draft', 'pending', 'approved', 'rejected','dipinjam', 'menunggu_konfirmasi_kembali', 'dikembalikan',
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
