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
    Schema::table('permintaans', function (Blueprint $table) {
			$table->enum('status_permintaan', ['draft','pending','menunggu_spv','approved','rejected'])->default('draft')->change();
        // $table->foreignId('verified_by_admin')->nullable()->after('request_by')->constrained('users');
        // $table->timestamp('verified_at_admin')->nullable()->after('verified_by_admin');
        // $table->text('catatan_admin')->nullable()->after('verified_at_admin');
    });

   }

public function down(): void
{
			$table->enum('status_permintaan', ['draft','pending','menunggu_spv','approved','rejected'])->nullable()->default('draft');

    // Schema::table('permintaans', function (Blueprint $table) {
    //     $table->dropForeign(['verified_by_admin']);
    //     $table->dropColumn(['verified_by_admin', 'verified_at_admin', 'catatan_admin']);
    // });

        }
};
