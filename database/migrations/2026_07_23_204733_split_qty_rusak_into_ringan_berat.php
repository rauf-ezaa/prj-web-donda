<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
          Schema::table('pengembalian_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_rusak_ringan')->default(0)->after('qty_baik');
            $table->unsignedInteger('qty_rusak_berat')->default(0)->after('qty_rusak_ringan');
        });

        // pindahkan data lama qty_rusak -> qty_rusak_ringan (anggap semua rusak lama itu ringan, default aman)
        DB::table('pengembalian_items')->update([
            'qty_rusak_ringan' => DB::raw('qty_rusak'),
        ]);


        // peminjaman_items — kolom akumulasi
        Schema::table('peminjaman_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_kembali_rusak_ringan')->default(0)->after('qty_kembali_baik');
            $table->unsignedInteger('qty_kembali_rusak_berat')->default(0)->after('qty_kembali_rusak_ringan');
        });

        DB::table('peminjaman_items')->update([
            'qty_kembali_rusak_ringan' => DB::raw('qty_kembali_rusak'),
        ]);

        Schema::table('peminjaman_items', function (Blueprint $table) {
            $table->dropColumn('qty_kembali_rusak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('pengembalian_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_rusak')->default(0)->after('qty_baik');

        });

        DB::table('pengembalian_items')->update([
            'qty_rusak' => DB::raw('qty_rusak_ringan + qty_rusak_berat'),
        ]);

        Schema::table('pengembalian_items', function (Blueprint $table) {
            $table->dropColumn(['qty_rusak_ringan', 'qty_rusak_berat']);
        });

        Schema::table('peminjaman_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_kembali_rusak')->default(0);
        });

        DB::table('peminjaman_items')->update([
            'qty_kembali_rusak' => DB::raw('qty_kembali_rusak_ringan + qty_kembali_rusak_berat'),
        ]);

        Schema::table('peminjaman_items', function (Blueprint $table) {
            $table->dropColumn(['qty_kembali_rusak_ringan', 'qty_kembali_rusak_berat']);
        });
    }
};
