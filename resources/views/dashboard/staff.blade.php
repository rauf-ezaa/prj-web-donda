@extends('layouts.app')
@php $currentPageTitle = 'Dashboard'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
            Selamat datang, {{ auth()->user()->nama_karyawan }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan aktivitas kamu hari ini.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
        <a href="{{ route('peminjaman.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Draft Peminjaman</p>
            <p class="text-2xl font-semibold text-amber-600">{{ $peminjaman_draft }}</p>
        </a>
        <a href="{{ route('peminjaman.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Peminjaman Berjalan</p>
            <p class="text-2xl font-semibold text-blue-600">{{ $peminjaman_berjalan }}</p>
        </a>
        <a href="{{ route('permintaan.index') }}" class="rounded-xl border border-gray-200 bg-white p-5 transition hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Permintaan Pending</p>
            <p class="text-2xl font-semibold text-orange-600">{{ $permintaan_pending }}</p>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-400">Peminjaman Terbaru</p>
            <div class="space-y-2">
                @forelse($peminjaman_terbaru as $p)
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2 last:border-0 dark:border-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $p->kode_peminjaman }}</span>
                        <span class="text-xs text-gray-400">{{ $p->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada peminjaman.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-400">Permintaan Terbaru</p>
            <div class="space-y-2">
                @forelse($permintaan_terbaru as $p)
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2 last:border-0 dark:border-gray-800">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $p->kode_permintaan }}</span>
                        <span class="text-xs text-gray-400">{{ $p->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada permintaan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
