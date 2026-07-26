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
			 DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status
            ENUM(
                'draft',
                'pending',
                'menunggu_spv',
                'approved',
                'rejected',
                'dipinjam',
                'sebagian_dikembalikan',
                'menunggu_konfirmasi_kembali',
                'dikembalikan',
                'selesai',
                'dibatalkan'
            ) DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
   	 			 DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status
            ENUM(
                'draft',
                'pending',
                'menunggu_spv',
                'approved',
                'rejected',
                'dipinjam',
                'sebagian_dikembalikan',
                'menunggu_konfirmasi_kembali',
                'dikembalikan',
                'selesai',
                'dibatalkan'
            ) DEFAULT 'draft'");
		}
};
