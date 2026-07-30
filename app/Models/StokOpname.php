<?php
// app/Models/StokOpname.php
namespace App\Models;

use App\Models\StokOpnameItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
		protected $table = 'stok_opnames';


    protected $fillable = [
        'periode_id', 'no_bast', 'tanggal_bast', 'catatan', 'bulan', 'tahun',
        'dibuat_oleh', 'status', 'diverifikasi_oleh', 'diverifikasi_at', 'catatan_cancel',
    ];

    protected $casts = [
        'tanggal_bast'     => 'date',
        'diverifikasi_at'  => 'datetime',
    ];

    public function getNamaBulanAttribute(): string
		{
				return Carbon::createFromDate($this->tahun, $this->bulan, 1)->translatedFormat('F Y');
		}


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
