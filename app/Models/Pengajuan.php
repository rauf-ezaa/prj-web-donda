<?php

namespace App\Models;

use App\Models\PengajuanDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{

		const DENIED_STATUS = ['menunggu_spv','approved','rejected'];

		public function getIsAccessibleAttribute(): bool
		{
			return !in_array($this->status, self::DENIED_STATUS);
		}

		public function getIsNotAccesibleAttribute(): bool
		{
			return $this->status === (['Approved','rejected']);
		}

		public function getIsActionableAttribute(): bool
		{
				return $this->status === 'menunggu_spv'; // cuma ini yang boleh diproses SPV
		}

		public function getIsActionableAdminAttribute(): bool
		{
				return $this->status === 'pending'; // cuma ini yang boleh diproses SPV
		}

		public function getIsEditableAttribute(): bool
		{
				return in_array($this->status, [ 'pending']);
		}

    protected $table = 'pengajuan';

    protected $fillable = [
    'kode_pengajuan', 'requested_by', 'alasan_pengajuan', 'status',
    'approved_by', 'approved_at', 'catatan_approval',
    'verified_by_admin', 'verified_at_admin',
];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

		public function verifiedByAdmin()
		{
				return $this->belongsTo(User::class, 'verified_by_admin');
		}

    public function details()
    {
        return $this->hasMany(PengajuanDetail::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getTotalEstimasiAttribute()
    {
        return $this->details->sum(fn($d) => $d->jumlah_diajukan * $d->estimasi_harga_satuan);
    }
}
