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
        Schema::table('pengajuan', function (Blueprint $table) {
            DB::statement("ALTER TABLE pengajuan MODIFY status ENUM('draft','pending','menunggu_spv','approved','rejected','dibatalkan') DEFAULT 'draft'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            DB::statement("ALTER TABLE pengajuan MODIFY status ENUM('draft','pending','menunggu_spv','approved','rejected','dibatalkan') DEFAULT 'draft'");
        });
    }
};
