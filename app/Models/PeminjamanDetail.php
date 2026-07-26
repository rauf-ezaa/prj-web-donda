<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $table = 'peminjaman_items';

    protected $fillable = [
        'peminjaman_id', 'barang_id', 'jumlah_pinjam',
        'jumlah_disetujui', 'kondisi_kembali', 'catatan_kembali',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

		public function sisaQty(): int
		{
				return $this->jumlah_pinjam - ($this->qty_kembali_baik + $this->qty_kembali_rusak + $this->qty_kembali_hilang);
		}

		public function getSisaQtyAttribute(): int
		{
				return $this->sisaQty();
		}

		public function recomputeStatus(): void
		{
				$total = $this->qty_kembali_baik + $this->qty_kembali_rusak + $this->qty_kembali_hilang;

				$this->status_kembali = match (true) {
						$total >= $this->jumlah_pinjam => 'selesai',
						$total > 0                    => 'sebagian',
						default                       => 'dipinjam',
				};

				$this->save();
		}
}
