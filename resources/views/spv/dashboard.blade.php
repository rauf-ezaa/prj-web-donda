@extends('layouts.app')
@php $currentPageTitle = 'Dashboard'; @endphp
@section('content')

<div class="max-w-4xl mx-auto">
    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-5">Dashboard</h3>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total jenis aset</p>
            <p class="text-2xl font-medium text-gray-800 dark:text-white/90">{{ number_format($totalJenisAset, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total unit aset</p>
            <p class="text-2xl font-medium text-gray-800 dark:text-white/90">{{ number_format($totalUnitAset, 0, ',', '.') }}</p>
        </div>
				<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
						<p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Barang masuk bulan ini</p>
						<p class="text-2xl font-medium text-gray-800 dark:text-white/90">{{ $barangMasukBulanIni }}</p>
				</div>

    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-amber-50 dark:bg-amber-950/30 rounded-xl p-4">
            <p class="text-xs text-amber-600 mb-1">Menunggu persetujuan</p>
            <p class="text-2xl font-medium text-amber-600">{{ $menungguPersetujuan }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Sedang dipinjam</p>
            <p class="text-2xl font-medium text-gray-800 dark:text-white/90">{{ $sedangDipinjam }}</p>
        </div>
        <div class="bg-red-50 dark:bg-red-950/30 rounded-xl p-4">
            <p class="text-xs text-error-500 mb-1">Terlambat kembali</p>
            <p class="text-2xl font-medium text-error-500">{{ $terlambatKembali }}</p>
        </div>
    </div>

    <x-common.component-card title="Perlu Tindakan Segera">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @if ($menungguPermintaan > 0)
                <a href="{{ route('spv.permintaan.index') }}" class="flex items-center py-3 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-4 px-4">
                    <i class="ti ti-clipboard-check text-lg text-amber-600 mr-3"></i>
                    <span class="text-sm text-gray-800 dark:text-white/90 flex-1">{{ $menungguPermintaan }} permintaan barang menunggu persetujuan</span>
                    <i class="ti ti-chevron-right text-gray-400"></i>
                </a>
            @endif

            @if ($menungguPengajuan > 0)
                <a href="{{ route('spv.pengajuan.index') }}" class="flex items-center py-3 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-4 px-4">
                    <i class="ti ti-file-text text-lg text-amber-600 mr-3"></i>
                    <span class="text-sm text-gray-800 dark:text-white/90 flex-1">{{ $menungguPengajuan }} pengajuan barang baru menunggu persetujuan</span>
                    <i class="ti ti-chevron-right text-gray-400"></i>
                </a>
            @endif

            @if ($menungguPersediaan > 0)
                <a href="{{ route('spv.persediaan.index') }}" class="flex items-center py-3 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-4 px-4">
                    <i class="ti ti-truck-delivery text-lg text-amber-600 mr-3"></i>
                    <span class="text-sm text-gray-800 dark:text-white/90 flex-1">{{ $menungguPersediaan }} barang masuk menunggu verifikasi</span>
                    <i class="ti ti-chevron-right text-gray-400"></i>
                </a>
            @endif

            @if ($menungguPeminjaman > 0)
                <a href="{{ route('spv.peminjaman.index') }}" class="flex items-center py-3 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-4 px-4">
                    <i class="ti ti-calendar-check text-lg text-amber-600 mr-3"></i>
                    <span class="text-sm text-gray-800 dark:text-white/90 flex-1">{{ $menungguPeminjaman }} peminjaman menunggu persetujuan</span>
                    <i class="ti ti-chevron-right text-gray-400"></i>
                </a>
            @endif

						 @if ($menungguPengembalian > 0)
                <a href="{{ route('spv.pengembalian.index') }}" class="flex items-center py-3 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-4 px-4">
                    <i class="ti ti-calendar-check text-lg text-amber-600 mr-3"></i>
                    <span class="text-sm text-gray-800 dark:text-white/90 flex-1">{{ $menungguPeminjaman }} peminjaman menunggu persetujuan</span>
                    <i class="ti ti-chevron-right text-gray-400"></i>
                </a>
            @endif

            @if ($terlambatKembali > 0)
                <a href="{{ route('spv.peminjaman.index') }}" class="flex items-center py-3 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-4 px-4">
                    <i class="ti ti-alert-triangle text-lg text-error-500 mr-3"></i>
                    <span class="text-sm text-gray-800 dark:text-white/90 flex-1">{{ $terlambatKembali }} peminjaman sudah melewati batas kembali</span>
                    <i class="ti ti-chevron-right text-gray-400"></i>
                </a>
            @endif

            @if ($menungguPersetujuan === 0 && $terlambatKembali === 0)
                <div class="py-8 text-center text-sm text-gray-400">
                    Tidak ada tindakan yang perlu dilakukan saat ini.
                </div>
            @endif
        </div>
    </x-common.component-card>
</div>

@endsection
