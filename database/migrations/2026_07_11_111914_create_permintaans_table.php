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
        Schema::create('permintaans', function (Blueprint $table) {
            $table->id();
						$table->string('kode_permintaan')->unique();
						$table->unsignedBigInteger('request_by')->constrained('users');;
						$table->unsignedBigInteger('approved_by')->nullable();
						$table->enum('status_permintaan', ['draft','menunggu', 'diterima','ditolak'])->default('draft');
						$table->dateTime('approved_date');
						$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
