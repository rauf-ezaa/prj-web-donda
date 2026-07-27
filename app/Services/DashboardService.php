<?php
namespace App\Services;

use App\Models\{Peminjaman, Permintaan, Pengajuan, Pembelian, Pengembalian, SaldoAwal, StokOpname};

class DashboardService
{
    public function dataStaff(int $userId): array
    {
        return [
            'peminjaman_draft'    => Peminjaman::where('requested_by', $userId)->where('status', 'draft')->count(),
            'peminjaman_berjalan' => Peminjaman::where('requested_by', $userId)->whereIn('status', ['dipinjam', 'sebagian_dikembalikan'])->count(),
            'permintaan_pending'  => Permintaan::where('request_by', $userId)->where('status_permintaan', 'pending')->count(),
            'pengajuan_draft'     => Pengajuan::where('requested_by', $userId)->where('status', 'draft')->count(),
            'peminjaman_terbaru'  => Peminjaman::where('requested_by', $userId)->latest()->take(5)->get(),
            'permintaan_terbaru'  => Permintaan::where('request_by', $userId)->latest()->take(5)->get(),
        ];
    }

    public function dataAdmin(): array
    {
        return [
            'permintaan_menunggu'    => Permintaan::where('status_permintaan', 'pending')->count(),
						'pengajuan_menunggu'		 => Pengajuan::where('status', 'pending')->count(),
            'pengembalian_menunggu'  => Pengembalian::where('status', 'menunggu_verifikasi_admin')->count(),
            'peminjaman_menunggu'    => Peminjaman::where('status', 'pending')->count(),
            'pembelian_menunggu'     => Pembelian::where('status', 'menunggu_verifikasi_spv')->count(), // pembelian admin yg buat, spv yg verify — tampilkan draft/riwayat sendiri
            'saldo_awal_menunggu'    => SaldoAwal::where('status', 'menunggu_verifikasi_spv')->count(),
            'aktivitas_terbaru'      => collect()
                ->merge(Permintaan::where('status_permintaan', 'pending')->latest()->take(3)->get()->map(fn($r) => ['label' => 'Permintaan', 'kode' => $r->kode_permintaan, 'tanggal' => $r->created_at]))
                ->merge(Pengembalian::where('status', 'menunggu_verifikasi_admin')->latest()->take(3)->get()->map(fn($r) => ['label' => 'Pengembalian', 'kode' => "#{$r->id}", 'tanggal' => $r->created_at]))
                ->merge(Peminjaman::where('status', 'pending')->latest()->take(3)->get()->map(fn($r) => ['label' => 'Peminjaman', 'kode' => $r->kode_peminjaman, 'tanggal' => $r->created_at]))
                ->merge(Pengajuan::where('status', 'pending')->latest()->take(3)->get()->map(fn($r) => ['label' => 'pengajuan', 'kode' => $r->kode_pengajuan, 'tanggal' => $r->created_at]))
                ->sortByDesc('tanggal')
                ->take(5),
        ];
    }

    public function dataSpv(): array
    {
        return [
            'permintaan_menunggu'    => Permintaan::where('status_permintaan', 'menunggu_spv')->count(),
            'peminjaman_menunggu'    => Peminjaman::where('status', 'menunggu_spv')->count(),
            'pengembalian_menunggu'  => Pengembalian::where('status', 'menunggu_verifikasi_spv')->count(),
            'pembelian_menunggu'     => Pembelian::where('status', 'menunggu_verifikasi_spv')->count(),
            'saldo_awal_menunggu'    => SaldoAwal::where('status', 'menunggu_verifikasi_spv')->count(),
            'stok_opname_menunggu'   => StokOpname::where('status', 'menunggu_verifikasi_spv')->count(),
            'aktivitas_terbaru'      => collect()
                ->merge(Peminjaman::where('status', 'menunggu_spv')->latest()->take(3)->get()->map(fn($r) => ['label' => 'Peminjaman', 'kode' => $r->kode_peminjaman, 'tanggal' => $r->created_at]))
                ->merge(Pengembalian::where('status', 'menunggu_verifikasi_spv')->latest()->take(3)->get()->map(fn($r) => ['label' => 'Pengembalian', 'kode' => "#{$r->id}", 'tanggal' => $r->created_at]))
                ->merge(StokOpname::where('status', 'menunggu_verifikasi_spv')->latest()->take(3)->get()->map(fn($r) => ['label' => 'Stok Opname', 'kode' => $r->no_bast, 'tanggal' => $r->created_at]))
                ->sortByDesc('tanggal')
                ->take(5),
        ];
    }
}
