<?php
namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\PengembalianItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PengembalianService
{


public function createReturn(Peminjaman $peminjaman, array $itemsInput, int $staffId, ?string $catatan = null): Pengembalian
{
    return DB::transaction(function () use ($peminjaman, $itemsInput, $staffId, $catatan) {

        $adaYangPending = $peminjaman->pengembalians()
            ->whereIn('status', ['menunggu_verifikasi_admin', 'menunggu_verifikasi_spv'])
            ->exists();

        if ($adaYangPending) {
            throw new InvalidArgumentException(
                'Masih ada pengajuan pengembalian yang menunggu verifikasi. Selesaikan verifikasi itu dulu sebelum mengajukan pengembalian baru.'
            );
        }

        $totalQty = collect($itemsInput)->sum(
            fn ($row) => ($row['qty_baik'] ?? 0)
                + ($row['qty_rusak_ringan'] ?? 0)
                + ($row['qty_rusak_berat'] ?? 0)
                + ($row['qty_hilang'] ?? 0)
                + ($row['qty_habis_terpakai'] ?? 0)
        );

        if ($totalQty <= 0) {
            throw new InvalidArgumentException('Tidak ada barang yang diinput untuk dikembalikan.');
        }

        $pengembalian = Pengembalian::create([
            'peminjaman_id'        => $peminjaman->id,
            'dikembalikan_oleh'    => $staffId,
            'tanggal_pengembalian' => now(),
            'catatan'              => $catatan,
            'status'               => 'menunggu_verifikasi_admin',
        ]);

        foreach ($itemsInput as $row) {
            $peminjamanItem = $peminjaman->items()->lockForUpdate()->findOrFail($row['peminjaman_item_id']);

            $qtyDiinput = ($row['qty_baik'] ?? 0)
                + ($row['qty_rusak_ringan'] ?? 0)
                + ($row['qty_rusak_berat'] ?? 0)
                + ($row['qty_hilang'] ?? 0)
                + ($row['qty_habis_terpakai'] ?? 0);

            if ($qtyDiinput <= 0) continue;

            if ($qtyDiinput > $peminjamanItem->sisa_qty) {
                throw new InvalidArgumentException(
                    "Qty pengembalian {$peminjamanItem->barang->nama_barang} melebihi sisa qty ({$peminjamanItem->sisa_qty})."
                );
            }

            PengembalianItem::create([
                'pengembalian_id'    => $pengembalian->id,
                'peminjaman_item_id' => $peminjamanItem->id,
                'qty_baik'           => $row['qty_baik'] ?? 0,
                'qty_rusak_ringan'   => $row['qty_rusak_ringan'] ?? 0,
                'qty_rusak_berat'    => $row['qty_rusak_berat'] ?? 0,
                'qty_hilang'         => $row['qty_hilang'] ?? 0,
                'qty_habis_terpakai' => $row['qty_habis_terpakai'] ?? 0,
            ]);
        }

        return $pengembalian->load('items');
    });
}
    /**
     * TAHAP 1 — Admin. Cuma pindah status ke antrian SPV, stok TIDAK berubah.
     */
    public function verifyByAdmin(Pengembalian $pengembalian, int $adminId): void
    {
        if ($pengembalian->status !== 'menunggu_verifikasi_admin') {
            throw new InvalidArgumentException('Pengembalian ini bukan di tahap verifikasi admin.');
        }

        $pengembalian->update([
            'status'                  => 'menunggu_verifikasi_spv',
            'diverifikasi_admin_oleh' => $adminId,
            'diverifikasi_admin_at'   => now(),
        ]);
    }

    public function rejectByAdmin(Pengembalian $pengembalian, int $adminId, string $alasan): void
    {
        if ($pengembalian->status !== 'menunggu_verifikasi_admin') {
            throw new InvalidArgumentException('Pengembalian ini bukan di tahap verifikasi admin.');
        }

        $pengembalian->update([
            'status'                  => 'ditolak_admin',
            'diverifikasi_admin_oleh' => $adminId,
            'diverifikasi_admin_at'   => now(),
            'alasan_tolak'            => $alasan,
        ]);
    }

    /**
     * TAHAP 2 — Supervisor. Di sinilah stok & status kumulatif item baru diupdate.
     */
public function verifyBySpv(Pengembalian $pengembalian, int $spvId): void
{
    if ($pengembalian->status !== 'menunggu_verifikasi_spv') {
        throw new InvalidArgumentException('Pengembalian ini bukan di tahap verifikasi supervisor.');
    }

    DB::transaction(function () use ($pengembalian, $spvId) {
        foreach ($pengembalian->items as $item) {
            $peminjamanItem = $item->peminjamanItem;

            $peminjamanItem->qty_kembali_baik         += $item->qty_baik;
            $peminjamanItem->qty_kembali_rusak_ringan += $item->qty_rusak_ringan;
            $peminjamanItem->qty_kembali_rusak_berat  += $item->qty_rusak_berat;
            $peminjamanItem->qty_kembali_hilang       += $item->qty_hilang;
            $peminjamanItem->qty_kembali_habis_terpakai += $item->qty_habis_terpakai;
            $peminjamanItem->save();
            $peminjamanItem->recomputeStatus();

            // baik + rusak ringan + rusak berat = masuk stok. hilang + habis_terpakai = tidak.
            $barang = $peminjamanItem->barang()->lockForUpdate()->first();
            $barang->increment('stok_tersedia', $item->qty_baik + $item->qty_rusak_ringan + $item->qty_rusak_berat);
        }

        $pengembalian->update([
            'status'                => 'selesai',
            'diverifikasi_spv_oleh' => $spvId,
            'diverifikasi_spv_at'   => now(),
        ]);

        $pengembalian->peminjaman->refreshStatus();
    });
}

    public function rejectBySpv(Pengembalian $pengembalian, int $spvId, string $alasan): void
    {
        if ($pengembalian->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Pengembalian ini bukan di tahap verifikasi supervisor.');
        }

        // penting: karena stok belum pernah disentuh di tahap admin, reject di sini juga aman, gak perlu rollback stok
        $pengembalian->update([
            'status'                => 'ditolak_spv',
            'diverifikasi_spv_oleh' => $spvId,
            'diverifikasi_spv_at'   => now(),
            'alasan_tolak'          => $alasan,
        ]);
    }
}
