<?php
namespace App\Services;

use App\Models\SaldoAwal;
use App\Models\SaldoAwalItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SaldoAwalService
{

  public function create(array $itemsInput, string $tanggalPencatatan, int $adminId, ?string $catatan, int $periodeId, ?int $stokOpnameId = null): SaldoAwal
{
    return DB::transaction(function () use ($itemsInput, $tanggalPencatatan, $adminId, $catatan, $periodeId, $stokOpnameId) {

        $totalQty = collect($itemsInput)->sum(fn ($row) => $row['qty'] ?? 0);
        if ($totalQty <= 0) {
            throw new InvalidArgumentException('Minimal harus ada satu barang dengan qty lebih dari 0.');
        }

        $saldoAwal = SaldoAwal::create([
            'no_transaksi'       => $this->generateNoTransaksi(),
            'periode_id'         => $periodeId,
            'tanggal_pencatatan' => $tanggalPencatatan,
            'catatan'            => $catatan,
            'sumber'             => $stokOpnameId ? 'dari_opname' : 'manual',
            'stok_opname_id'     => $stokOpnameId,
            'dibuat_oleh'        => $adminId,
            'status'             => 'menunggu_verifikasi_spv',
        ]);

        foreach ($itemsInput as $row) {
            if (($row['qty'] ?? 0) <= 0) continue;

            SaldoAwalItem::create([
                'saldo_awal_id' => $saldoAwal->id,
                'barang_id'     => $row['barang_id'],
                'qty'           => $row['qty'],
            ]);
        }

        return $saldoAwal->load('items');
    });
}

    public function verify(SaldoAwal $saldoAwal, int $spvId): void
    {
        if ($saldoAwal->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Saldo awal ini bukan di tahap menunggu verifikasi.');
        }

        DB::transaction(function () use ($saldoAwal, $spvId) {
            foreach ($saldoAwal->items as $item) {
                $barang = $item->barang()->lockForUpdate()->first();
                $barang->increment('stok_tersedia', $item->qty);
            }

            $saldoAwal->update([
                'status'            => 'selesai',
                'diverifikasi_oleh' => $spvId,
                'diverifikasi_at'   => now(),
            ]);
        });
    }

    public function reject(SaldoAwal $saldoAwal, int $spvId, string $alasan): void
    {
        if ($saldoAwal->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Saldo awal ini bukan di tahap menunggu verifikasi.');
        }

        $saldoAwal->update([
            'status'            => 'ditolak',
            'diverifikasi_oleh' => $spvId,
            'diverifikasi_at'   => now(),
            'alasan_tolak'      => $alasan,
        ]);
    }

    protected function generateNoTransaksi(): string
    {
        $prefix = 'SLA-' . now()->format('Ymd') . '-';
        $lastNumber = SaldoAwal::where('no_transaksi', 'like', $prefix . '%')->count();

        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
