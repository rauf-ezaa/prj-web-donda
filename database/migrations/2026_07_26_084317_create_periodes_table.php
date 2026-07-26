<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->unsignedSmallInteger('tahun');
            $table->enum('status', ['aktif', 'terkunci'])->default('aktif');
            $table->foreignId('dikunci_oleh')->nullable()->constrained('users');
            $table->dateTime('dikunci_at')->nullable();
            $table->timestamps();

            $table->unique(['semester', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
