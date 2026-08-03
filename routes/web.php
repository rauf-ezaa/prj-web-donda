<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardAssetAdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPdfController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PermintaanApprovalController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;


require __DIR__.'/auth.php';

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->dashboardRoute());
    }
    return redirect()->route('login');
})->name('guest.home');

Route::middleware(['auth', 'role:admin|spv'])->get('statistik/barang', [LaporanController::class, 'statistik'])->name('laporan.statistik');
Route::middleware(['auth', 'role:admin|spv'])->get('statistik/pengajuan', [LaporanController::class, 'dataPengajuan'])->name('laporan.pengajuan');
Route::middleware(['auth','role:staf'])->get('riwayat-saya/statistik', [RiwayatController::class, 'statistikSaya'])->name('riwayat.statistik');

Route::middleware(['auth', 'role:admin|spv'])->prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/{modul}', [LaporanController::class, 'modul'])->name('modul');
    Route::get('/{modul}/export-pdf', [LaporanController::class, 'exportPdf'])->name('modul.export-pdf');
    Route::get('/{modul}/{id}/detail-pdf', [LaporanController::class, 'detailPdf'])->name('modul.detail-pdf');
});

	Route::middleware(['auth','role.redirect:staf'])->group(function () {
		Route::post('/permintaan/{permintaan}/items', [PermintaanController::class, 'addItem'])->name('permintaan.items.add');
		Route::post('/permintaan/{permintaan}/verifikasi', [PermintaanController::class, 'verifikasi'])->name('permintaan.verifikasi');
		Route::middleware('auth')->get('riwayat-saya', [RiwayatController::class, 'index'])->name('riwayat.index');
		require __DIR__ .'/Pengajuan/pengajuan.php';
		require __DIR__ .'/Permintaan/permintaan.php';
		require __DIR__ .'/Peminjaman/peminjaman.php';
		require __DIR__ .'/Pengembalian/pengembalian.php';
		Route::get('riwayat-saya', [RiwayatController::class, 'index'])->name('riwayat.index');
	});


Route::middleware(['auth'])->group(function () {
			require __DIR__ .'/dashboard.php';
	});

	Route::middleware(['auth', 'role.redirect:admin'])->prefix('admin')->group(function () {
		require __DIR__.'/persediaan/persediaan.php';
		require __DIR__.'/kib/kib.php';
		require __DIR__ .'/Permintaan/adminPermintaan.php';
		require __DIR__ .'/karyawan/karyawan.php';
		require __DIR__ .'/Peminjaman/adminPeminjaman.php';
		require __DIR__ .'/Pengajuan/adminPengajuan.php';
		require __DIR__ .'/Pengembalian/adminPengembalian.php';
		require __DIR__ .'/MasterBarang/crudBarang.php';
		require __DIR__ .'/Pembelian/adminPembelian.php';
		require __DIR__ .'/SaldoAwal/adminSaldoAwal.php';
		require __DIR__ .'/StokOpname/adminStockOpname.php';


			Route::get('/sarpras',[DashboardAssetAdminController::class,'getHalamanSarpras'])->name('admin.sarpas');
			Route::get('/atk',[DashboardAssetAdminController::class,'getHalamanAtk'])->name('admin.atk');
			Route::get('/asset-tetap',[DashboardAssetAdminController::class,'getHalamanKIB'])->name('kib');
});

Route::middleware(['auth', 'role.redirect:spv'])->group(function () {
		require __DIR__ .'/Pengajuan/spvPengajuan.php';
		require __DIR__ .'/Peminjaman/spvPeminjaman.php';
		require __DIR__.'/persediaan/spvPersediaan.php';
		require __DIR__ .'/Pengembalian/spvPengembalian.php';
		require __DIR__.'/kategori/kategori.php';
		require __DIR__ .'/Pembelian/spvPembelian.php';
		require __DIR__ .'/SaldoAwal/spvSaldoAwal.php';
		require __DIR__ .'/StokOpname/spvStockOpname.php';
		require __DIR__ .'/Periode/spvPeriode.php';



		Route::get('/verifikasi-permintaan', [PermintaanApprovalController::class, 'index'])->name('permintaan.index.admin');
    Route::get('/verifkasi-permintaan/{permintaan}', [PermintaanApprovalController::class, 'show'])->name('permintaan.show.admin');
    Route::post('/verifikasi-permintaan/{permintaan}/approve', [PermintaanApprovalController::class, 'approve'])->name('permintaan.approve');
    Route::post('/verifikasi-permintaan/{permintaan}/reject', [PermintaanApprovalController::class, 'reject'])->name('permintaan.reject');

});





	// Route::middlewarea
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
