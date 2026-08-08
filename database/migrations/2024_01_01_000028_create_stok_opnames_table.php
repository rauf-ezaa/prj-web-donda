<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->string('no_bast')->nullable();
            $table->date('tanggal_bast')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->enum('status', ['draft', 'menunggu_verifikasi_spv', 'selesai', 'dibatalkan_spv'])
                ->default('draft');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users');
            $table->dateTime('diverifikasi_at')->nullable();
            $table->text('catatan_cancel')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
    }
};
