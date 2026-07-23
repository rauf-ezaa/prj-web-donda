<?php

namespace App\Models;

use App\Models\Peminjaman;
use App\Models\PengembalianItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id', 'dikembalikan_oleh', 'tanggal_pengembalian', 'catatan',
        'status', 'diverifikasi_admin_oleh', 'diverifikasi_admin_at',
        'diverifikasi_spv_oleh', 'diverifikasi_spv_at', 'alasan_tolak',
    ];

    protected $casts = [
        'tanggal_pengembalian'   => 'datetime',
        'diverifikasi_admin_at'  => 'datetime',
        'diverifikasi_spv_at'    => 'datetime',
    ];

    public function peminjaman() { return $this->belongsTo(Peminjaman::class); }
    public function items() { return $this->hasMany(PengembalianItem::class); }
    public function staff() { return $this->belongsTo(User::class, 'dikembalikan_oleh'); }
    public function adminVerifikator() { return $this->belongsTo(User::class, 'diverifikasi_admin_oleh'); }
    public function spvVerifikator() { return $this->belongsTo(User::class, 'diverifikasi_spv_oleh'); }


    public function verifikator() { return $this->belongsTo(User::class, 'diverifikasi_oleh'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu_verifikasi_admin' => 'Menunggu Verifikasi Admin',
            'menunggu_verifikasi_spv'   => 'Menunggu Verifikasi Supervisor',
            'selesai'                   => 'Selesai',
            'ditolak_admin'             => 'Ditolak Admin',
            'ditolak_spv'               => 'Ditolak Supervisor',
        };
    }
}
