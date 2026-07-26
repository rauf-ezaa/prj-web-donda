<?php
// app/Models/StokOpname.php
namespace App\Models;

use App\Models\Periode;
use App\Models\StokOpnameItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
		protected $table = 'stok_opnames';
    protected $fillable = [
        'periode_id', 'no_bast', 'tanggal_bast', 'catatan',
        'dibuat_oleh', 'status', 'diverifikasi_oleh', 'diverifikasi_at', 'catatan_cancel',
    ];

    protected $casts = [
        'tanggal_bast'     => 'date',
        'diverifikasi_at'  => 'datetime',
    ];

    public function periode() { return $this->belongsTo(Periode::class); }
    public function items() { return $this->hasMany(StokOpnameItem::class); }
    public function dibuatOleh() { return $this->belongsTo(User::class, 'dibuat_oleh'); }
    public function diverifikasiOleh() { return $this->belongsTo(User::class, 'diverifikasi_oleh'); }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'                    => 'Draft',
            'menunggu_verifikasi_spv'  => 'Menunggu Verifikasi Supervisor',
            'selesai'                  => 'Selesai',
            'dibatalkan_spv'           => 'Dibatalkan Supervisor — Perlu Revisi',
            default                    => ucfirst($this->status),
        };
    }
}
