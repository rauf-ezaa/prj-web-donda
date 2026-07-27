@extends('layouts.app')
@php $currentPageTitle = 'Dashboard Supervisor'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Dashboard Supervisor</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Verifikasi final yang menunggu keputusan kamu.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
        <a href="{{ route('spv.permintaan.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Permintaan Menunggu</p>
            <p class="text-2xl font-semibold text-amber-600">{{ $permintaan_menunggu }}</p>
        </a>
        <a href="{{ route('spv.peminjaman.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Peminjaman Menunggu</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $peminjaman_menunggu }}</p>
        </a>
        <a href="{{ route('spv.pengembalian.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pengembalian Menunggu</p>
            <p class="text-2xl font-semibold text-orange-600">{{ $pengembalian_menunggu }}</p>
        </a>
        <a href="{{ route('spv.pembelian.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pembelian Menunggu</p>
            <p class="text-2xl font-semibold text-teal-600">{{ $pembelian_menunggu }}</p>
        </a>
        <a href="{{ route('spv.saldo-awal.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Saldo Awal Menunggu</p>
            <p class="text-2xl font-semibold text-purple-600">{{ $saldo_awal_menunggu }}</p>
        </a>
        <a href="{{ route('spv.stok-opname.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Stok Opname Menunggu</p>
            <p class="text-2xl font-semibold text-pink-600">{{ $stok_opname_menunggu }}</p>
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-400">Aktivitas Terbaru yang Perlu Ditindak</p>
        <div class="space-y-2">
            @forelse($aktivitas_terbaru as $a)
                <div class="flex items-center justify-between border-b border-gray-100 pb-2 last:border-0 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">{{ $a['label'] }}</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $a['kode'] }}</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $a['tanggal']->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Tidak ada aktivitas yang perlu ditindak saat ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
