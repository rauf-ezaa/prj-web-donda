<?php
namespace App\Models;

use App\Models\Periode;
use App\Models\SaldoAwalItem;
use App\Models\StokOpname;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SaldoAwal extends Model
{
		protected $table = 'saldo_awal';
    protected $fillable = [
        'no_transaksi', 'tanggal_pencatatan', 'catatan',
        'dibuat_oleh', 'status', 'diverifikasi_oleh', 'diverifikasi_at', 'alasan_tolak',
    ];

    protected $casts = [
        'tanggal_pencatatan' => 'date',
        'diverifikasi_at'    => 'datetime',
    ];

		public function periode() { return $this->belongsTo(Periode::class); }
		public function stokOpname() { return $this->belongsTo(StokOpname::class); }

    public function items()
    {
        return $this->hasMany(SaldoAwalItem::class);
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
