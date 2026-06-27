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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 100);
            $table->float('harga_barang');
            $table->integer('stok_tersedia');
            $table->text('description');
            $table->unsignedBigInteger('klasifikasi_kib');
            $table->foreign('klasifikasi_kib')->references('id')->on('kib')->onDelete('cascade');
            $table->timestamps();
            // yang belum ada kategori KIB dan asal dana 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
