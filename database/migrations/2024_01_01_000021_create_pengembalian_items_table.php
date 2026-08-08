<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengembalian_id')->constrained('pengembalians')->cascadeOnDelete();
            $table->unsignedInteger('qty_baik')->default(0);
            $table->unsignedInteger('qty_rusak_ringan')->default(0);
            $table->unsignedInteger('qty_rusak_berat')->default(0);
            $table->unsignedInteger('qty_rusak')->default(0);
            $table->unsignedInteger('qty_hilang')->default(0);
            $table->unsignedInteger('qty_habis_terpakai')->default(0);
            $table->timestamps();
            $table->foreignId('peminjaman_item_id')->constrained('peminjaman_items');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian_items');
    }
};
