<?php
namespace App\Services;

use App\Models\{Peminjaman, Permintaan, Pengajuan, Pembelian, Pengembalian, SaldoAwal, StokOpname};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Daftar modul yang bisa dilaporkan. Nambah modul baru cukup tambah di sini.
     */
public function daftarModul(): array
{
    return [
        'peminjaman' => [
            'label' => 'Peminjaman', 'model' => Peminjaman::class,
            'kolom_kode' => 'kode_peminjaman', 'kolom_user' => 'requested_by',
            'relasi_items' => 'items.barang', 'nama_relasi_items' => 'items',
            'kolom_item' => [
                ['label' => 'Barang', 'field' => 'barang.nama_barang'],
                ['label' => 'Qty Pinjam', 'field' => 'qty_pinjam'],
                ['label' => 'Kembali Baik', 'field' => 'qty_kembali_baik'],
                ['label' => 'Rusak Ringan', 'field' => 'qty_kembali_rusak_ringan'],
                ['label' => 'Rusak Berat', 'field' => 'qty_kembali_rusak_berat'],
                ['label' => 'Hilang', 'field' => 'qty_kembali_hilang'],
            ],

						'status_boleh_cetak' => ['approved','rejected','dipinjam','dikembalikan','selesai','dibatalkan'],
        ],
        'permintaan' => [
            'label' => 'Permintaan', 'model' => Permintaan::class,
            'kolom_kode' => 'kode_permintaan', 'kolom_user' => 'request_by',
            'relasi_items' => 'details.barang', 'nama_relasi_items' => 'details',
            'kolom_item' => [
                ['label' => 'Barang', 'field' => 'barang.nama_barang'],
                ['label' => 'Diminta', 'field' => 'jumlah_diminta'],
                ['label' => 'Disetujui', 'field' => 'jumlah_disetujui'],
            ],

						'status_boleh_cetak' => ['approved', 'rejected'],
        ],
        'pengajuan' => [
            'label' => 'Pengajuan', 'model' => Pengajuan::class,
            'kolom_kode' => 'kode_pengajuan', 'kolom_user' => 'requested_by',
            'relasi_items' => 'details', 'nama_relasi_items' => 'details',
            'kolom_item' => [
                ['label' => 'Nama Barang Diajukan', 'field' => 'nama_barang_diajukan'],
                ['label' => 'Jumlah Diajukan', 'field' => 'jumlah_diajukan'],
            ],
					'status_boleh_cetak' => ['approved', 'rejected', 'dibatalkan'],
        ],
        'pembelian' => [
            'label' => 'Pembelian', 'model' => Pembelian::class,
            'kolom_kode' => 'no_transaksi', 'kolom_user' => 'dibuat_oleh',
            'relasi_items' => 'items.barang', 'nama_relasi_items' => 'items',
            'kolom_item' => [
                ['label' => 'Barang', 'field' => 'barang.nama_barang'],
                ['label' => 'Qty', 'field' => 'qty'],
                ['label' => 'Deskripsi', 'field' => 'deskripsi'],
            ],
						 'status_boleh_cetak' => ['selesai', 'ditolak'],

        ],
        'pengembalian' => [
            'label' => 'Pengembalian', 'model' => Pengembalian::class,
            'kolom_kode' => 'id', 'kolom_user' => 'dikembalikan_oleh',
            'relasi_items' => 'items.peminjamanItem.barang', 'nama_relasi_items' => 'items',
            'kolom_item' => [
                ['label' => 'Barang', 'field' => 'peminjamanItem.barang.nama_barang'],
                ['label' => 'Baik', 'field' => 'qty_baik'],
                ['label' => 'Rusak Ringan', 'field' => 'qty_rusak_ringan'],
                ['label' => 'Rusak Berat', 'field' => 'qty_rusak_berat'],
                ['label' => 'Hilang', 'field' => 'qty_hilang'],
                ['label' => 'Habis Terpakai', 'field' => 'qty_habis_terpakai'],
            ],
						 'status_boleh_cetak' => ['selesai', 'ditolak_admin', 'ditolak_spv'],
        ],
        'saldo_awal' => [
            'label' => 'Saldo Awal', 'model' => SaldoAwal::class,
            'kolom_kode' => 'no_transaksi', 'kolom_user' => 'dibuat_oleh',
            'relasi_items' => 'items.barang', 'nama_relasi_items' => 'items',
            'kolom_item' => [
                ['label' => 'Barang', 'field' => 'barang.nama_barang'],
                ['label' => 'Qty', 'field' => 'qty'],
            ],

						  'status_boleh_cetak' => ['selesai','ditolak_admin','ditolak_spv'],
        ],
        'stok_opname' => [
            'label' => 'Stok Opname', 'model' => StokOpname::class,
            'kolom_kode' => 'no_bast', 'kolom_user' => 'dibuat_oleh',
            'relasi_items' => 'items.barang', 'nama_relasi_items' => 'items',
            'kolom_item' => [
                ['label' => 'Barang', 'field' => 'barang.nama_barang'],
                ['label' => 'Stok Sistem', 'field' => 'stok_sistem'],
                ['label' => 'Stok Fisik', 'field' => 'stok_fisik'],
                ['label' => 'Selisih', 'field' => 'selisih'],
            ],
						 'status_boleh_cetak' => ['selesai','dibatalkan_spv'],
        ],
    ];
}

    /**
     * Riwayat aktivitas milik 1 user, digabung dari semua modul, diurutkan berdasarkan tanggal terbaru.
     */
    public function riwayatUser(int $userId): Collection
    {
        $hasil = collect();

        foreach ($this->daftarModul() as $key => $modul) {
            $rows = $modul['model']::where($modul['kolom_user'], $userId)
                ->latest()
                ->get();

            foreach ($rows as $row) {
                $hasil->push([
                    'modul'       => $key,
                    'modul_label' => $modul['label'],
                    'kode'        => $row->{$modul['kolom_kode']} ?? "#{$row->id}",
                    'status'      => $row->status ?? $row->status_permintaan ?? $row->status_pemintaan ?? '-',
                    'tanggal'     => $row->created_at,
                    'id'          => $row->id,
                ]);
            }
        }

        return $hasil->sortByDesc('tanggal')->values();
    }

    /**
     * Laporan 1 modul spesifik untuk admin/spv, dengan filter status & rentang tanggal.
     */
    public function laporanModul(string $modulKey, array $filter = [])
    {
        $modul = $this->daftarModul()[$modulKey] ?? null;
        abort_unless($modul, 404, 'Modul tidak dikenali.');

        $query = $modul['model']::query();

        if (!empty($filter['status'])) {
            $kolomStatus = $this->kolomStatusFor($modulKey);
            $query->where($kolomStatus, $filter['status']);
        }

        if (!empty($filter['dari_tanggal'])) {
            $query->whereDate('created_at', '>=', $filter['dari_tanggal']);
        }

        if (!empty($filter['sampai_tanggal'])) {
            $query->whereDate('created_at', '<=', $filter['sampai_tanggal']);
        }

        return $query->latest()->paginate(15)->withQueryString();
    }

    protected function kolomStatusFor(string $modulKey): string
    {
        return match ($modulKey) {
            'permintaan' => 'status_permintaan',
            default      => 'status',
        };
    }

    /**
     * Ringkasan angka per modul, buat dashboard laporan admin/spv.
     */
    public function ringkasanSemuaModul(): array
    {
        $ringkasan = [];

        foreach ($this->daftarModul() as $key => $modul) {
            $ringkasan[$key] = [
                'label' => $modul['label'],
                'total' => $modul['model']::count(),
            ];
        }

        return $ringkasan;
    }

		public function laporanModulUntukExport(string $modulKey, array $filter = [])
{
    $modul = $this->daftarModul()[$modulKey] ?? null;
    abort_unless($modul, 404, 'Modul tidak dikenali.');

    $query = $modul['model']::query();

    if (!empty($filter['status'])) {
        $kolomStatus = $this->kolomStatusFor($modulKey);
        $query->where($kolomStatus, $filter['status']);
    }

    if (!empty($filter['dari_tanggal'])) {
        $query->whereDate('created_at', '>=', $filter['dari_tanggal']);
    }

    if (!empty($filter['sampai_tanggal'])) {
        $query->whereDate('created_at', '<=', $filter['sampai_tanggal']);
    }

    return $query->latest()->get();
}

public function detailTransaksi(string $modulKey, int $id)
{
    $modul = $this->daftarModul()[$modulKey] ?? null;
    abort_unless($modul, 404, 'Modul tidak dikenali.');

    $row = $modul['model']::with($modul['relasi_items'])->findOrFail($id);

    return ['modul' => $modul, 'row' => $row];
}

/**
 * Ambil nilai field yang mungkin bertingkat, misal 'barang.nama_barang'.
 */
public function ambilNilaiField($object, string $field)
{
    $segments = explode('.', $field);
    $value = $object;

    foreach ($segments as $segment) {
        if ($value === null) return '-';
        $value = is_array($value) ? ($value[$segment] ?? null) : ($value->{$segment} ?? null);
    }

    return $value ?? '-';
}
public function detailPdf(string $modul, int $id)
{
    $daftarModul = $this->service->daftarModul();
    abort_unless(isset($daftarModul[$modul]), 404);

    ['modul' => $modulInfo, 'row' => $row] = $this->service->detailTransaksi($modul, $id);

    $namaRelasiItems = $modulInfo['nama_relasi_items'];
    $items = $row->{$namaRelasiItems};

    $pdf = Pdf::loadView('laporan.pdf.detail', [
        'modulKey'    => $modul,
        'modulInfo'   => $modulInfo,
        'row'         => $row,
        'items'       => $items,
        'kolomKode'   => $modulInfo['kolom_kode'],
        'service'     => $this->service,
    ])->setPaper('a4', 'portrait');

    $kode = $row->{$modulInfo['kolom_kode']} ?? "#{$row->id}";
    $namaFile = str_replace(['/', ' '], '-', "{$modulInfo['label']}-{$kode}") . '.pdf';

    return $pdf->stream($namaFile);
}



// STATTISTIK BLOK



public function statistikBarang(?int $userId = null)
{
    $hasil = collect();

    // Peminjaman
    $queryPeminjaman = DB::table('peminjaman_items')
        ->join('peminjaman', 'peminjaman_items.peminjaman_id', '=', 'peminjaman.id')
        ->join('barangs', 'peminjaman_items.barang_id', '=', 'barangs.id')
        ->select('barangs.id as barang_id', 'barangs.nama_barang', DB::raw('SUM(peminjaman_items.qty_pinjam) as total_qty'));

    if ($userId) {
        $queryPeminjaman->where('peminjaman.requested_by', $userId);
    }

    $peminjamanRows = $queryPeminjaman->groupBy('barangs.id', 'barangs.nama_barang')->get();

    foreach ($peminjamanRows as $row) {
        $this->tambahKeHasil($hasil, $row->barang_id, $row->nama_barang, 'peminjaman', $row->total_qty);
    }

    // Permintaan
    $queryPermintaan = DB::table('permintaan_details')
        ->join('permintaans', 'permintaan_details.permintaan_id', '=', 'permintaans.id')
        ->join('barangs', 'permintaan_details.barang_id', '=', 'barangs.id')
        ->where('permintaans.status_permintaan', 'approved')
        ->select('barangs.id as barang_id', 'barangs.nama_barang', DB::raw('SUM(permintaan_details.jumlah_disetujui) as total_qty'));

    if ($userId) {
        $queryPermintaan->where('permintaans.request_by', $userId);
    }

    $permintaanRows = $queryPermintaan->groupBy('barangs.id', 'barangs.nama_barang')->get();

    foreach ($permintaanRows as $row) {
        $this->tambahKeHasil($hasil, $row->barang_id, $row->nama_barang, 'permintaan', $row->total_qty);
    }

    // Pembelian (cuma relevan untuk laporan global admin, bukan "kebiasaan user" karena ini bukan user yang minta barang)
    if (!$userId) {
        $pembelianRows = DB::table('pembelian_items')
            ->join('pembelians', 'pembelian_items.pembelian_id', '=', 'pembelians.id')
            ->join('barangs', 'pembelian_items.barang_id', '=', 'barangs.id')
            ->where('pembelians.status', 'selesai')
            ->select('barangs.id as barang_id', 'barangs.nama_barang', DB::raw('SUM(pembelian_items.qty) as total_qty'))
            ->groupBy('barangs.id', 'barangs.nama_barang')
            ->get();

        foreach ($pembelianRows as $row) {
            $this->tambahKeHasil($hasil, $row->barang_id, $row->nama_barang, 'pembelian', $row->total_qty);
        }
    }

    // Urutkan berdasarkan total keseluruhan (semua modul digabung), dari besar ke kecil
    return $hasil->sortByDesc('total_keseluruhan')->values();
}

		protected function tambahKeHasil(&$hasil, $barangId, $namaBarang, string $modul, int $qty): void
		{
				$existing = $hasil->firstWhere('barang_id', $barangId);

				if ($existing) {
						$existing[$modul] = ($existing[$modul] ?? 0) + $qty;
						$existing['total_keseluruhan'] += $qty;
						$hasil = $hasil->map(fn ($item) => $item['barang_id'] === $barangId ? $existing : $item);
				} else {
						$hasil->push([
								'barang_id'          => $barangId,
								'nama_barang'        => $namaBarang,
								'peminjaman'         => $modul === 'peminjaman' ? $qty : 0,
								'permintaan'         => $modul === 'permintaan' ? $qty : 0,
								'pembelian'          => $modul === 'pembelian' ? $qty : 0,
								'total_keseluruhan'  => $qty,
						]);
				}
		}
}
