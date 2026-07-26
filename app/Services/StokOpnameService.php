<?php
namespace App\Services;

use App\Models\{Barang, Periode, StokOpname, SaldoAwal};
use App\Models\StokOpnameItem;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class StokOpnameService
{
    public function __construct(private OpnameLockService $lock) {}

    public function start(Periode $periode, int $adminId): StokOpname
    {
        $this->lock->assertNotLocked();

        if ($periode->isTerkunci()) {
            throw new InvalidArgumentException('Periode ini sedang terkunci, tidak dapat memulai opname.');
        }

        return DB::transaction(function () use ($periode, $adminId) {
            $stokOpname = StokOpname::create([
                'periode_id'  => $periode->id,
                'dibuat_oleh' => $adminId,
                'status'      => 'draft',
            ]);

            $barangList = Barang::all();

            foreach ($barangList as $barang) {
                StokOpnameItem::create([
                    'stok_opname_id' => $stokOpname->id,
                    'barang_id'      => $barang->id,
                    'stok_sistem'    => $barang->stok_tersedia,
                    'stok_fisik'     => null,
                    'selisih'        => 0,
                ]);
            }

            return $stokOpname->load('items.barang');
        });
    }

    /**
     * Admin isi stok fisik + BAST, lalu submit ke SPV.
     * Ini titik krusial: SETELAH ini, sistem TERKUNCI (assertNotLocked di tempat lain bakal mulai nolak).
     */
    public function submit(StokOpname $stokOpname, array $itemsInput, string $noBast, string $tanggalBast, ?string $catatan, int $adminId): void
    {
        if (!in_array($stokOpname->status, ['draft', 'dibatalkan_spv'])) {
            throw new InvalidArgumentException('Opname ini tidak dapat diajukan pada status saat ini.');
        }

        DB::transaction(function () use ($stokOpname, $itemsInput, $noBast, $tanggalBast, $catatan) {
            foreach ($itemsInput as $row) {
                $item = $stokOpname->items()->findOrFail($row['item_id']);

                $stokFisik = (int) ($row['stok_fisik'] ?? 0);
                $selisih = $stokFisik - $item->stok_sistem;

                if ($selisih !== 0 && empty(trim($row['keterangan'] ?? ''))) {
                    throw new InvalidArgumentException(
                        "Keterangan wajib diisi untuk barang dengan selisih (item ID {$item->id})."
                    );
                }

                $item->update([
                    'stok_fisik' => $stokFisik,
                    'selisih'    => $selisih,
                    'keterangan' => $row['keterangan'] ?? null,
                ]);
            }

            $stokOpname->update([
                'no_bast'         => $noBast,
                'tanggal_bast'    => $tanggalBast,
                'catatan'         => $catatan,
                'status'          => 'menunggu_verifikasi_spv',
                'catatan_cancel'  => null,
            ]);
        });
    }

    /**
     * SPV setuju — stok_tersedia dikoreksi sesuai hasil fisik, periode & lock lepas.
     */
    public function verify(StokOpname $stokOpname, int $spvId): void
    {
        if ($stokOpname->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Opname ini bukan di tahap menunggu verifikasi.');
        }

        DB::transaction(function () use ($stokOpname, $spvId) {
            foreach ($stokOpname->items as $item) {
                $barang = $item->barang()->lockForUpdate()->first();
                $barang->update(['stok_tersedia' => $item->stok_fisik]);
            }

            $stokOpname->update([
                'status'            => 'selesai',
                'diverifikasi_oleh' => $spvId,
                'diverifikasi_at'   => now(),
            ]);
        });
    }

    /**
     * SPV cancel — BUKAN ditolak permanen, balik ke admin buat direvisi. Lock ikut lepas.
     */
    public function cancel(StokOpname $stokOpname, int $spvId, string $catatanCancel): void
    {
        if ($stokOpname->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Opname ini bukan di tahap menunggu verifikasi.');
        }

        $stokOpname->update([
            'status'           => 'dibatalkan_spv',
            'diverifikasi_oleh' => $spvId,
            'diverifikasi_at'  => now(),
            'catatan_cancel'   => $catatanCancel,
        ]);
    }

    /**
     * SPV kunci periode — syarat: minimal ada 1 opname selesai di periode itu.
     */
    public function kunciPeriode(Periode $periode, int $spvId): void
    {
        $adaOpnameSelesai = $periode->stokOpnames()->where('status', 'selesai')->exists();

        if (!$adaOpnameSelesai) {
            throw new InvalidArgumentException('Periode ini belum memiliki Stok Opname yang selesai diverifikasi, tidak dapat dikunci.');
        }

        $periode->update([
            'status'       => 'terkunci',
            'dikunci_oleh' => $spvId,
            'dikunci_at'   => now(),
        ]);
    }

    public function bukaKunciPeriode(Periode $periode): void
    {
        $periode->update(['status' => 'aktif', 'dikunci_oleh' => null, 'dikunci_at' => null]);
    }

    /**
     * Dipanggil saat admin buka Saldo Awal untuk periode baru —
     * auto-generate draft dari opname terakhir periode SEBELUMNYA yang selesai.
     */
    public function draftSaldoAwalDariOpnameTerakhir(Periode $periodeSebelumnya): ?StokOpname
    {
        return $periodeSebelumnya->stokOpnames()
            ->where('status', 'selesai')
            ->latest()
            ->first();
    }
}
