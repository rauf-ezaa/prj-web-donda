<?php

// xxxx_create_stok_opname_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_opname_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('stok_sistem');
            $table->integer('stok_fisik')->nullable();
            $table->integer('selisih')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['stok_opname_id', 'barang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opname_items');
    }
};
