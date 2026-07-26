<?php
namespace App\Services;

use App\Models\{Peminjaman, Permintaan, Pengajuan, Pembelian, Pengembalian, SaldoAwal, StokOpname};
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Daftar modul yang bisa dilaporkan. Nambah modul baru cukup tambah di sini.
     */
    public function daftarModul(): array
    {
        return [
            'peminjaman'    => ['label' => 'Peminjaman', 'model' => Peminjaman::class, 'kolom_kode' => 'kode_peminjaman', 'kolom_user' => 'requested_by'],
            'permintaan'    => ['label' => 'Permintaan', 'model' => Permintaan::class, 'kolom_kode' => 'kode_permintaan', 'kolom_user' => 'request_by'],
            'pengajuan'     => ['label' => 'Pengajuan', 'model' => Pengajuan::class, 'kolom_kode' => 'kode_pengajuan', 'kolom_user' => 'requested_by'],
            'pembelian'     => ['label' => 'Pembelian', 'model' => Pembelian::class, 'kolom_kode' => 'no_transaksi', 'kolom_user' => 'dibuat_oleh'],
            'pengembalian'  => ['label' => 'Pengembalian', 'model' => Pengembalian::class, 'kolom_kode' => 'id', 'kolom_user' => 'dikembalikan_oleh'],
            'saldo_awal'    => ['label' => 'Saldo Awal', 'model' => SaldoAwal::class, 'kolom_kode' => 'no_transaksi', 'kolom_user' => 'dibuat_oleh'],
            'stok_opname'   => ['label' => 'Stok Opname', 'model' => StokOpname::class, 'kolom_kode' => 'no_bast', 'kolom_user' => 'dibuat_oleh'],
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
}
