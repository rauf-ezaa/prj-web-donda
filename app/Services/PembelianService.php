<?php
namespace App\Services;

use App\Models\Pembelian;
use App\Models\PembelianItem;
use App\Models\SaldoAwal;
use App\Models\SaldoAwalItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PembelianService
{
    public function create(array $itemsInput, string $tanggalPencatatan, int $adminId, ?string $catatan = null): SaldoAwal
{
    return DB::transaction(function () use ($itemsInput, $tanggalPencatatan, $adminId, $catatan) {

        $totalQty = collect($itemsInput)->sum(fn ($row) => $row['qty'] ?? 0);

        if ($totalQty <= 0) {
            throw new InvalidArgumentException('Minimal harus ada satu barang dengan qty lebih dari 0.');
        }

        $saldoAwal = SaldoAwal::create([
            'no_transaksi'       => $this->generateNoTransaksi(),
            'tanggal_pencatatan' => $tanggalPencatatan,
            'catatan'            => $catatan,
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

    /**
     * Verifikasi SPV — di sinilah stok_tersedia baru bertambah.
     */
    public function verify(Pembelian $pembelian, int $spvId): void
    {
        if ($pembelian->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Pembelian ini bukan di tahap menunggu verifikasi.');
        }

        DB::transaction(function () use ($pembelian, $spvId) {
            foreach ($pembelian->items as $item) {
                $barang = $item->barang()->lockForUpdate()->first();
                $barang->increment('stok_tersedia', $item->qty);
            }

            $pembelian->update([
                'status'            => 'selesai',
                'diverifikasi_oleh' => $spvId,
                'diverifikasi_at'   => now(),
            ]);
        });
    }

    public function reject(Pembelian $pembelian, int $spvId, string $alasan): void
    {
        if ($pembelian->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Pembelian ini bukan di tahap menunggu verifikasi.');
        }

        $pembelian->update([
            'status'            => 'ditolak',
            'diverifikasi_oleh' => $spvId,
            'diverifikasi_at'   => now(),
            'alasan_tolak'      => $alasan,
        ]);
    }

    protected function generateNoTransaksi(): string
    {
        $prefix = 'PMB-' . now()->format('Ymd') . '-';
        $lastNumber = Pembelian::where('no_transaksi', 'like', $prefix . '%')->count();

        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
