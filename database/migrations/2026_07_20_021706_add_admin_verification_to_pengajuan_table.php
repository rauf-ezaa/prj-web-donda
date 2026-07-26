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
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->foreignId('verified_by_admin')->nullable()->after('requested_by')->constrained('users');
        $table->timestamp('verified_at_admin')->nullable()->after('verified_by_admin');
        $table->text('catatan_admin')->nullable()->after('verified_at_admin');
        });
				DB::statement("ALTER TABLE pengajuan MODIFY status ENUM('draft','pending','menunggu_spv','approved','rejected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
          Schema::table('pengajuan', function (Blueprint $table) {
        $table->dropForeign(['verified_by_admin']);
        $table->dropColumn(['verified_by_admin', 'verified_at_admin', 'catatan_admin']);
    });

    DB::statement("ALTER TABLE pengajuan MODIFY status ENUM('draft','pending','approved','rejected') DEFAULT 'draft'");
        });
    }
};
