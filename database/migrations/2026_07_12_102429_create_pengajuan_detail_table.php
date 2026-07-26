<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            // $table->foreignId('barang_id')->constrained('barangs');
            $table->unsignedInteger('jumlah_diajukan');
            $table->decimal('estimasi_harga_satuan', 15, 2);
            $table->text('catatan_item')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_detail');
    }
};
