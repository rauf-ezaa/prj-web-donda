<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_permintaan')->unique();
            // Catatan: request_by di DDL asli TIDAK punya FK constraint (hanya kolom biasa)
            $table->unsignedBigInteger('request_by');
            $table->foreignId('verified_by_admin')->nullable()->constrained('users');
            $table->timestamp('verified_at_admin')->nullable();
            $table->text('catatan_admin')->nullable();
            // Catatan: approved_by di DDL asli TIDAK punya FK constraint
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->enum('status_permintaan', ['draft', 'pending', 'menunggu_spv', 'approved', 'rejected'])
                ->default('draft');
            $table->dateTime('approved_date')->nullable();
            $table->timestamps();
            $table->text('keperluan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
