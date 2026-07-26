<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
     protected $fillable = [
        'no_transaksi', 'nama_supplier', 'tanggal_diterima', 'catatan',
        'dibuat_oleh', 'status', 'diverifikasi_oleh', 'diverifikasi_at', 'alasan_tolak',
    ];

    protected $casts = [
        'tanggal_diterima' => 'date',
        'diverifikasi_at'  => 'datetime',
    ];

		 public function items()
    {
        return $this->hasMany(PembelianItem::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function diverifikasiOleh()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi_spv' => 'Menunggu Verifikasi Supervisor',
            'selesai'                 => 'Selesai',
            'ditolak'                 => 'Ditolak',
            default                   => ucfirst($this->status),
        };
    }

}
