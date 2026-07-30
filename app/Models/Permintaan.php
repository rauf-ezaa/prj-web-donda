<?php

namespace App\Models;

use App\Models\PermintaanDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{

		const DENIED_STATUS = ['rejected','menunggu_spv'];

		public function getIsAccessibleAttribute(): bool
		{
			return !in_array($this->status_permintaan, self::DENIED_STATUS);
		}

		public function getIsEditableAttribute(): bool
		{
				return in_array($this->status_permintaan, [ 'pending']);
		}

		public function getIsActionableAttribute(): bool
		{
				return $this->status_permintaan === 'pending'; // cuma ini yang boleh diproses SPV
		}

		public function getIsSpvAttribute(): bool
		{
				return $this->status_permintaan === 'menunggu_spv'; // cuma ini yang boleh diproses SPV
		}



		protected $table = 'permintaans';
    protected $fillable = ['kode_permintaan', 'request_by', 'keperluan', 'status_permintaan', 'approved_by', 'approved_date', 'catatan_approval'];
    protected $casts = ['approved_date' => 'datetime'];


		public function verifiedByAdmin()
		{
				return $this->belongsTo(User::class, 'verified_by_admin');
		}

		 public function details()
    {
        return $this->hasMany(PermintaanDetail::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'request_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
