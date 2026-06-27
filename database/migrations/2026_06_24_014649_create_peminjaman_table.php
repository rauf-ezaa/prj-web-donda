<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
                $table->id();

                $table->string('nomor_peminjaman')->unique();

                $table->foreignId('karyawan_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->date('tanggal_pinjam');

                $table->date('tanggal_kembali_rencana');

                $table->enum('status', [
                    'dipinjam',
                    'dikembalikan',
                    'terlambat'
                ])->default('dipinjam');

                $table->text('keterangan')->nullable();

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
