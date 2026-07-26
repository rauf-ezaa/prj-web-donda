<?php
namespace App\Services;

use App\Models\StokOpname;
use Illuminate\Http\Exceptions\HttpResponseException;

class OpnameLockService
{
    public function isLocked(): bool
    {
        return StokOpname::where('status', 'menunggu_verifikasi_spv')->exists();
    }

    public function activeLock(): ?StokOpname
    {
        return StokOpname::where('status', 'menunggu_verifikasi_spv')->first();
    }

    public function assertNotLocked(): void
    {
        $opname = $this->activeLock();

        if ($opname) {
            throw new HttpResponseException(
                back()->withErrors([
                    'opname_lock' => "Sistem sedang dalam proses Stok Opname ({$opname->no_bast}) yang menunggu keputusan supervisor. Transaksi yang mempengaruhi stok dibekukan sementara sampai opname ini diverifikasi atau dibatalkan.",
                ])
            );
        }
    }
}
