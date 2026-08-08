<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan', 100);
            $table->string('nrk', 15)->unique();
            $table->string('nip', 20)->unique()->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamps();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
