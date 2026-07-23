<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Model;


class PeminjamanItem extends Model
{

		protected $table = 'peminjaman_items';
    protected $fillable = [
      'peminjaman_id', 'barang_id', 'qty_pinjam', 'jumlah_disetujui',
    'qty_kembali_baik', 'qty_kembali_rusak_ringan', 'qty_kembali_rusak_berat',
    'qty_kembali_hilang', 'qty_kembali_habis_terpakai',
    'status',
    ];

		public function sisaQty(): int
{
    return $this->qty_pinjam - (
        $this->qty_kembali_baik
        + $this->qty_kembali_rusak_ringan
        + $this->qty_kembali_rusak_berat
        + $this->qty_kembali_hilang
        + $this->qty_kembali_habis_terpakai
    );
}

public function getSisaQtyAttribute(): int
{
    return $this->sisaQty();
}

    public function peminjaman() { return $this->belongsTo(Peminjaman::class); }
    public function barang() { return $this->belongsTo(Barang::class); }


   public function recomputeStatus(): void
{
    $total = $this->qty_kembali_baik
        + $this->qty_kembali_rusak_ringan
        + $this->qty_kembali_rusak_berat
        + $this->qty_kembali_hilang
        + $this->qty_kembali_habis_terpakai;

    $this->status = match (true) {
        $total >= $this->qty_pinjam => 'selesai',
        $total > 0                  => 'sebagian',
        default                     => 'dipinjam',
    };

    $this->save();
}
}
