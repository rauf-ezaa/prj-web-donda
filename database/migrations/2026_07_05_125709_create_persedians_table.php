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
        Schema::create('persedians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
						$table->enum('asal_dana', ['bos', 'bop']);
						$table->integer('qty');
						$table->date('tanggal_masuk');
						$table->double('harga_satuan_unit', 12, 2)->nullable()->default(123.4567);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persedians');
    }
};
