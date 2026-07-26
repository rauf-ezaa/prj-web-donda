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
        Schema::table('pengembalian_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_habis_terpakai')->default(0)->after('qty_hilang');
        });

        Schema::table('peminjaman_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_kembali_habis_terpakai')->default(0)->after('qty_kembali_hilang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('pengembalian_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_habis_terpakai')->default(0)->after('qty_hilang');
        });

        Schema::table('peminjaman_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_kembali_habis_terpakai')->default(0)->after('qty_kembali_hilang');
        });
    }
};
