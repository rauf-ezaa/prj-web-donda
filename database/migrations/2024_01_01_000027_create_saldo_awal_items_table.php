<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_awal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saldo_awal_id')->constrained('saldo_awal')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->unsignedInteger('qty');
            $table->timestamps();
            $table->unique(['saldo_awal_id', 'barang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_awal_items');
    }
};
