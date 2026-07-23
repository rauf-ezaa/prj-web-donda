<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Persedian;
use App\Models\Permintaan;
use App\Models\Pengajuan;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJenisAset = Barang::count();
        $totalUnitAset = Barang::sum('stok_tersedia');
        $estimasiNilaiAset = Persedian::where('approval_status', 'diterima')->sum('harga_total');
        $stokMenipis = Barang::where('stok_tersedia', '<', 5)->count();

        $menungguPermintaan = Permintaan::where('status_permintaan', 'pending')->count();
        $menungguPengajuan = Pengajuan::where('status', 'pending')->count();
        $menungguPeminjaman = Peminjaman::where('status', 'pending')->count();
        $menungguPersediaan = Persedian::where('approval_status', 'menunggu')->count();
        $menungguPersetujuan = $menungguPermintaan + $menungguPengajuan + $menungguPeminjaman + $menungguPersediaan;

        $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();

        $terlambatKembali = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_wajib_kembali', '<', now())
            ->count();

        $barangMasukBulanIni = Persedian::where('approval_status', 'diterima')
            ->whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->count();

        return view('spv.dashboard', compact(
            'totalJenisAset',
            'totalUnitAset',
            'estimasiNilaiAset',
            'stokMenipis',
            'menungguPersetujuan',
            'menungguPermintaan',
            'menungguPengajuan',
            'menungguPeminjaman',
            'menungguPersediaan',
            'sedangDipinjam',
            'terlambatKembali',
            'barangMasukBulanIni',
        ));
    }
}
