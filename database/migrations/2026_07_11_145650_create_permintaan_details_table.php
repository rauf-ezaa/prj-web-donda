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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_details');
    }
};
