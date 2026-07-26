@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi peminjaman'; @endphp
@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-5">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Verifikasi peminjaman Barang</h3>

        <form method="GET" action="{{ route('admin.peminjaman.index') }}">
            <select
                name="status"
                onchange="this.form.submit()"
                class="dark:bg-dark-900 h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
            >
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                    Menunggu Persetujuan ({{ $counts['pending'] }})
                </option>
								<option value="menunggu_spv" {{ $status === 'menunggu_spv' ? 'selected' : '' }}>
                    Menunggu Persetujuan Supervisor ({{ $counts['menunggu_spv'] }})
                </option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>
                    Disetujui ({{ $counts['approved'] }})
                </option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                    Ditolak ({{ $counts['rejected'] }})
                </option>
                <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>
                    Semua Riwayat
                </option>
            </select>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-common.component-card :title="match($status) {
        'pending' => 'Menunggu Persetujuan Admin',
        'menunggu_spv' => 'Menunggu Persetujuan Supervisor',
        'approved' => 'Riwayat Disetujui',
        'rejected' => 'Riwayat Ditolak',
        default => 'Semua Riwayat',
    }">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
    @forelse ($peminjaman as $item)
        @php
						$rowClasses = 'flex items-center justify-between px-4 py-3 '
								. (!$loop->last ? 'border-b border-gray-200 dark:border-gray-700 ' : '')
								. ($item->is_accessible  ? 'hover:bg-gray-50 dark:hover:bg-gray-800' : 'opacity-60 cursor-not-allowed');
				@endphp

        @if ($item->is_accessible)
            <a href="{{ route('admin.peminjaman.show', $item->id) }}" class="{{ $rowClasses }}">
        @else
            <div class="{{ $rowClasses }}">
        @endif
            <div>
                <p class="text-sm text-gray-800 dark:text-white/90 m-0">{{ $item->kode_peminjaman }}</p>
                <p class="text-xs text-gray-400 m-0">
                    {{ $item->requestedBy->nama_karyawan ?? '-' }} · {{ $item->keperluan }}
                </p>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full whitespace-nowrap
                @if($item->status === 'approved') bg-green-50 text-green-700
                @elseif($item->status === 'rejected') bg-red-50 text-error-500
								 @elseif($item->status === 'menunggu_spv') bg-red-50 text-error-500
                @else bg-amber-50 text-amber-600
                @endif">
                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
            </span>

        @if ($item->is_accessible)
            </a>
        @else
            </div>
        @endif
    @empty
        <div class="p-6 text-center text-sm text-gray-400">
            Tidak ada data untuk filter ini.
        </div>
    @endforelse
</div>
    </x-common.component-card>
</div>

@endsection
