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
        Schema::table('persedians', function (Blueprint $table) {
            $table->enum('approval_status', ['menunggu', 'diterima','ditolak'])->default('menunggu');
						$table->text('catatan_approval')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persedians', function (Blueprint $table) {
             $table->enum('approval_status', ['menunggu', 'diterima','ditolak'])->default('menunggu');
						$table->text('catatan_approval')->nullable()->default('text');
        });
    }
};
