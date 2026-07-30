<?php
namespace App\Services;

use App\Models\{Barang, SaldoAwal, Pembelian, Pengembalian, Peminjaman, Permintaan, StokOpname, StokOpnameItem};
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Carbon\Carbon;

class StokOpnameService
{
    public function __construct(private OpnameLockService $lock) {}

    /**
     * Tanggal saldo awal PALING AWAL yang sudah diverifikasi SPV.
     * Ini jadi batas bawah — admin gak boleh opname untuk bulan sebelum ini.
     */
    public function tanggalSaldoAwalPertama(): ?Carbon
    {
        $saldoAwal = SaldoAwal::where('status', 'selesai')
            ->orderBy('tanggal_pencatatan')
            ->first();

        return $saldoAwal?->tanggal_pencatatan;
    }

    public function bulanBolehDipilih(int $bulan, int $tahun): bool
    {
        $batasBawah = $this->tanggalSaldoAwalPertama();

        if (!$batasBawah) {
            return false; // belum ada saldo awal sama sekali, gak boleh opname
        }

        $awalBulanDipilih = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $awalBulanBatas   = $batasBawah->copy()->startOfMonth();

        return $awalBulanDipilih->gte($awalBulanBatas);
    }

    /**
     * Hitung ulang stok_sistem per barang, dari Saldo Awal + akumulasi transaksi
     * yang sudah diverifikasi SPV, dibatasi sampai akhir bulan yang dipilih.
     */
    public function hitungStokSistem(int $bulan, int $tahun): array
    {
        $batasAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth()->endOfDay();

        $hasil = []; // [barang_id => qty]

        // 1. Saldo Awal — base, semua yang sudah selesai (gak terikat tanggal, ini titik nol)
        $saldoAwalRows = DB::table('saldo_awal_items')
            ->join('saldo_awal', 'saldo_awal_items.saldo_awal_id', '=', 'saldo_awal.id')
            ->where('saldo_awal.status', 'selesai')
            ->select('saldo_awal_items.barang_id', DB::raw('SUM(saldo_awal_items.qty) as total'))
            ->groupBy('saldo_awal_items.barang_id')
            ->get();

        foreach ($saldoAwalRows as $row) {
            $hasil[$row->barang_id] = ($hasil[$row->barang_id] ?? 0) + $row->total;
        }

        // 2. Pembelian — nambah, dibatasi diverifikasi_at <= batas akhir
        $pembelianRows = DB::table('pembelian_items')
            ->join('pembelians', 'pembelian_items.pembelian_id', '=', 'pembelians.id')
            ->where('pembelians.status', 'selesai')
            ->where('pembelians.diverifikasi_at', '<=', $batasAkhir)
            ->select('pembelian_items.barang_id', DB::raw('SUM(pembelian_items.qty) as total'))
            ->groupBy('pembelian_items.barang_id')
            ->get();

        foreach ($pembelianRows as $row) {
            $hasil[$row->barang_id] = ($hasil[$row->barang_id] ?? 0) + $row->total;
        }

        // 3. Pengembalian (baik + rusak ringan + rusak berat) — nambah, dibatasi diverifikasi_spv_at
        $pengembalianRows = DB::table('pengembalian_items')
            ->join('pengembalians', 'pengembalian_items.pengembalian_id', '=', 'pengembalians.id')
            ->join('peminjaman_items', 'pengembalian_items.peminjaman_item_id', '=', 'peminjaman_items.id')
            ->where('pengembalians.status', 'selesai')
            ->where('pengembalians.diverifikasi_spv_at', '<=', $batasAkhir)
            ->select(
                'peminjaman_items.barang_id',
                DB::raw('SUM(pengembalian_items.qty_baik + pengembalian_items.qty_rusak_ringan + pengembalian_items.qty_rusak_berat) as total')
            )
            ->groupBy('peminjaman_items.barang_id')
            ->get();

        foreach ($pengembalianRows as $row) {
            $hasil[$row->barang_id] = ($hasil[$row->barang_id] ?? 0) + $row->total;
        }

        // 4. Peminjaman — mengurangi, dibatasi approved_at
        $peminjamanRows = DB::table('peminjaman_items')
            ->join('peminjaman', 'peminjaman_items.peminjaman_id', '=', 'peminjaman.id')
            ->whereIn('peminjaman.status', ['dipinjam', 'sebagian_dikembalikan', 'selesai'])
            ->where('peminjaman.approved_at', '<=', $batasAkhir)
            ->select('peminjaman_items.barang_id', DB::raw('SUM(peminjaman_items.qty_pinjam) as total'))
            ->groupBy('peminjaman_items.barang_id')
            ->get();

        foreach ($peminjamanRows as $row) {
            $hasil[$row->barang_id] = ($hasil[$row->barang_id] ?? 0) - $row->total;
        }

        // 5. Permintaan — mengurangi, dibatasi approved_date
        $permintaanRows = DB::table('permintaan_details')
            ->join('permintaans', 'permintaan_details.permintaan_id', '=', 'permintaans.id')
            ->where('permintaans.status_permintaan', 'approved')
            ->where('permintaans.approved_date', '<=', $batasAkhir)
            ->select('permintaan_details.barang_id', DB::raw('SUM(permintaan_details.jumlah_disetujui) as total'))
            ->groupBy('permintaan_details.barang_id')
            ->get();

        foreach ($permintaanRows as $row) {
            $hasil[$row->barang_id] = ($hasil[$row->barang_id] ?? 0) - $row->total;
        }

        return $hasil; // [barang_id => qty_terhitung]
    }

    public function start(int $bulan, int $tahun, int $adminId): StokOpname
    {
        $this->lock->assertNotLocked();

        if (!$this->bulanBolehDipilih($bulan, $tahun)) {
            throw new InvalidArgumentException('Bulan yang dipilih tidak valid. Pastikan sudah ada Saldo Awal yang diverifikasi dan bulan tidak lebih awal dari itu.');
        }

        $sudahAda = StokOpname::where('bulan', $bulan)->where('tahun', $tahun)
            ->whereIn('status', ['draft', 'menunggu_verifikasi_spv', 'selesai'])
            ->exists();

        if ($sudahAda) {
            throw new InvalidArgumentException('Sudah ada opname untuk bulan ini (draft/berjalan/selesai).');
        }

        return DB::transaction(function () use ($bulan, $tahun, $adminId) {
            $stokOpname = StokOpname::create([
                'bulan'       => $bulan,
                'tahun'       => $tahun,
                'dibuat_oleh' => $adminId,
                'status'      => 'draft',
            ]);

            $stokSistemPerBarang = $this->hitungStokSistem($bulan, $tahun);

            foreach (Barang::all() as $barang) {
                StokOpnameItem::create([
                    'stok_opname_id' => $stokOpname->id,
                    'barang_id'      => $barang->id,
                    'stok_sistem'    => $stokSistemPerBarang[$barang->id] ?? 0,
                    'stok_fisik'     => null,
                    'selisih'        => 0,
                ]);
            }

            return $stokOpname->load('items.barang');
        });
    }

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
                    throw new InvalidArgumentException("Keterangan wajib diisi untuk barang dengan selisih (item ID {$item->id}).");
                }

                $item->update(['stok_fisik' => $stokFisik, 'selisih' => $selisih, 'keterangan' => $row['keterangan'] ?? null]);
                // $item->update([ 'selisih' => $selisih, 'keterangan' => $row['keterangan'] ?? null]);
            }

            $stokOpname->update([
                'no_bast' => $noBast, 'tanggal_bast' => $tanggalBast, 'catatan' => $catatan,
                'status' => 'menunggu_verifikasi_spv', 'catatan_cancel' => null,
            ]);
        });
    }

    /**
     * SPV setuju — stok_tersedia di master Barang disesuaikan ke hasil FISIK opname.
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
                'status' => 'selesai', 'diverifikasi_oleh' => $spvId, 'diverifikasi_at' => now(),
            ]);
        });
    }

    public function cancel(StokOpname $stokOpname, int $spvId, string $catatanCancel): void
    {
        if ($stokOpname->status !== 'menunggu_verifikasi_spv') {
            throw new InvalidArgumentException('Opname ini bukan di tahap menunggu verifikasi.');
        }

        $stokOpname->update([
            'status' => 'dibatalkan_spv', 'diverifikasi_oleh' => $spvId,
            'diverifikasi_at' => now(), 'catatan_cancel' => $catatanCancel,
        ]);
    }
}
