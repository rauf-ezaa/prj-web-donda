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
       Schema::create('pengembalian_items', function (Blueprint $table) {
				$table->id();
				$table->foreignId('pengembalian_id')->constrained()->cascadeOnDelete();
				// $table->foreignId('peminjaman_item_id')->constrained();
				$table->unsignedInteger('qty_baik')->default(0);
				$table->unsignedInteger('qty_rusak')->default(0);
				$table->unsignedInteger('qty_hilang')->default(0);
				$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian_items');
    }
};
