<?php

// xxxx_add_periode_and_source_to_saldo_awals_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('saldo_awal', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->after('id')->constrained();
            $table->enum('sumber', ['manual', 'dari_opname'])->default('manual')->after('catatan');
            $table->foreignId('stok_opname_id')->nullable()->after('sumber')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('saldo_awal', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropForeign(['stok_opname_id']);
            $table->dropColumn(['periode_id', 'sumber', 'stok_opname_id']);
        });
    }
};
