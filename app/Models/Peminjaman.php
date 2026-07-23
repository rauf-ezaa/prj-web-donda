<?php

namespace App\Models;

use App\Models\PeminjamanDetail;
use App\Models\PeminjamanItem;
use App\Models\Pengembalian;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
		const DENIED_STATUS = ['menunggu_spv',];

		public function getIsAccessibleAttribute(): bool
		{
			return !in_array($this->status, self::DENIED_STATUS);
		}

		public function getIsActionableAttribute(): bool
		{
				return $this->status === 'pending'; // cuma ini yang boleh diproses SPV
		}

		public function getIsSpvAttribute(): bool
		{
				return $this->status === 'menunggu_spv' ; // cuma ini yang boleh diproses SPV
		}

			public function getIsEditableAttribute(): bool
		{
				return in_array($this->status, [ 'pending']);
		}

		public function getIsAccessibleSpvAttribute(): bool
		{
			return true;
		}


		protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman', 'requested_by', 'keperluan',
        'tanggal_pinjam', 'tanggal_wajib_kembali', 'status',
        'approved_by', 'approved_at', 'catatan_approval', 'dikembalikan_at',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_wajib_kembali' => 'date',
        'approved_at' => 'datetime',
        'dikembalikan_at' => 'datetime',
    ];

		public function items() { return $this->hasMany(PeminjamanItem::class); }
    public function pengembalians() { return $this->hasMany(Pengembalian::class); }

    public function details()
    {
        return $this->hasMany(PeminjamanItem::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function getIsTerlambatAttribute()
    {
        return $this->status === 'dipinjam' && now()->gt($this->tanggal_wajib_kembali);
    }

		 public function refreshStatus(): void
    {
        $items = $this->items()->get();

        $this->status = match (true) {
            $items->every(fn ($i) => $i->status === 'selesai') => 'selesai',
            $items->contains(fn ($i) => $i->status !== 'dipinjam') => 'sebagian_dikembalikan',
            default => $this->status, // biarkan status approval awal (dipinjam/disetujui) kalau belum ada return sama sekali
        };

        $this->save();
    }

}
