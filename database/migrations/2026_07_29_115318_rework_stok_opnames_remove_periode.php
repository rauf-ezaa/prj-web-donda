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
        Schema::table('stok_opnames', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
            $table->unsignedSmallInteger('bulan')->after('id'); // 1-12
            $table->unsignedSmallInteger('tahun')->after('bulan');
        });

        Schema::table('stok_opname_items', function (Blueprint $table) {
            $table->integer('stok_sistem')->change(); // pastikan bisa nampung hasil hitung yang bisa besar
        });

        // saldo_awals juga gak perlu lagi terikat periode
        Schema::table('saldo_awal', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropForeign(['stok_opname_id']);
            $table->dropColumn(['periode_id', 'sumber', 'stok_opname_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('stok_opnames', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
            $table->foreignId('periode_id')->nullable()->constrained();
        });

        Schema::table('saldo_awal', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained();
            $table->enum('sumber', ['manual', 'dari_opname'])->default('manual');
            $table->foreignId('stok_opname_id')->nullable()->constrained();
        });
    }
};
