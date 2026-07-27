<?php
namespace App\Services;

use App\Models\Pembelian;
use App\Models\PembelianItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PembelianService
{
    public function createPembelian(array $itemsInput, string $namaSupplier, string $tanggalDiterima, int $adminId, ?string $catatan = null): Pembelian
    {
        return DB::transaction(function () use ($itemsInput, $namaSupplier, $tanggalDiterima, $adminId, $catatan) {

            $totalQty = collect($itemsInput)->sum(fn ($row) => $row['qty'] ?? 0);

            if ($totalQty <= 0) {
                throw new InvalidArgumentException('Minimal harus ada satu barang dengan qty lebih dari 0.');
            }

            $pembelian = Pembelian::create([
                'no_transaksi'     => $this->generateNoTransaksi(),
                'nama_supplier'    => $namaSupplier,
                'tanggal_diterima' => $tanggalDiterima,
                'catatan'          => $catatan,
                'dibuat_oleh'      => $adminId,
                'status'           => 'menunggu_verifikasi_spv',
            ]);

            foreach ($itemsInput as $row) {
                if (($row['qty'] ?? 0) <= 0) continue;

                PembelianItem::create([
                    'pembelian_id' => $pembelian->id,
                    'barang_id'    => $row['barang_id'],
                    'qty'          => $row['qty'],
                    'deskripsi'    => $row['deskripsi'] ?? null,
                ]);
            }

            return $pembelian->load('items');
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


		// tambahkan di app/Services/PembelianService.php

public function update(Pembelian $pembelian, array $itemsInput, string $namaSupplier, string $tanggalDiterima, ?string $catatan = null): Pembelian
{
    if ($pembelian->status !== 'menunggu_verifikasi_spv') {
        throw new InvalidArgumentException('Pembelian ini tidak dapat diedit karena sudah diverifikasi/ditolak.');
    }

    return DB::transaction(function () use ($pembelian, $itemsInput, $namaSupplier, $tanggalDiterima, $catatan) {

        $totalQty = collect($itemsInput)->sum(fn ($row) => $row['qty'] ?? 0);

        if ($totalQty <= 0) {
            throw new InvalidArgumentException('Minimal harus ada satu barang dengan qty lebih dari 0.');
        }

        $pembelian->update([
            'nama_supplier'    => $namaSupplier,
            'tanggal_diterima' => $tanggalDiterima,
            'catatan'          => $catatan,
        ]);

        // hapus item lama, gantikan dengan yang baru — lebih sederhana & aman
        // daripada diff satu-satu, karena belum ada stok yang berubah di tahap ini
        $pembelian->items()->delete();

        foreach ($itemsInput as $row) {
            if (($row['qty'] ?? 0) <= 0) continue;

            PembelianItem::create([
                'pembelian_id' => $pembelian->id,
                'barang_id'    => $row['barang_id'],
                'qty'          => $row['qty'],
                'deskripsi'    => $row['deskripsi'] ?? null,
            ]);
        }

        return $pembelian->load('items');
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
