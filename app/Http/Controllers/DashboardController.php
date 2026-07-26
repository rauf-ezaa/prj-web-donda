<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\Peminjaman;
use App\Models\Pengajuan;
use App\Models\Pengembalian;
use App\Models\Permintaan;
use App\Models\Persedian;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJenisAset = Barang::count();
        $totalUnitAset = Barang::sum('stok_tersedia');
        $estimasiNilaiAset = Persedian::where('approval_status', 'diterima')->sum('harga_total');
        $stokMenipis = Barang::where('stok_tersedia', '<', 5)->count();

        $menungguPermintaan = Permintaan::where('status_permintaan', 'menunggu_spv')->count();
        $menungguPengajuan = Pengajuan::where('status', 'menunggu_spv')->count();
        $menungguPeminjaman = Peminjaman::where('status', 'menunggu_spv')->count();
        $menungguPersediaan = Persedian::where('approval_status', 'menunggu')->count();
				$menungguPengembalian = Pengembalian::where('status','menunggu_verifikasi_spv')->count();
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
						'menungguPengembalian',
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

		public function dashboardAdmin(){
			$data = Karyawan::where('users_id',auth()->user()->id)->first();
			// dd($data);
				return view('dashboard.admin.index', compact('data'));
		}

		public function dashboardStaff(){
					$data = Karyawan::where('users_id',auth()->user()->id)->first();
			// dd($data);
				return view('dashboard.staff.index', compact('data'));
		}
}
