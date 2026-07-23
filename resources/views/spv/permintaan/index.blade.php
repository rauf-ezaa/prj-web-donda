@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Pengajuan'; @endphp
@section('content')
<div class="max-w-3xl mx-auto">
    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-5">Verifikasi Permintaan Barang</h3>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-common.component-card title="Menunggu Persetujuan">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            @forelse ($permintaan as $item)
                <a href="{{ route('spv.permintaan.show', $item->id) }}"
                   class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div>
                        <p class="text-sm text-gray-800 dark:text-white/90 m-0">{{ $item->kode_permintaan }}</p>
                        <p class="text-xs text-gray-400 m-0">
                            {{ $item->requestedBy->nama_karyawan ?? '-' }} · {{ $item->keperluan }}
                        </p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 whitespace-nowrap">
                        {{ $item->created_at->diffForHumans() }}
                    </span>
                </a>
            @empty
                <div class="p-6 text-center text-sm text-gray-400">
                    Tidak ada permintaan yang menunggu persetujuan.
                </div>
            @endforelse
        </div>
    </x-common.component-card>

    <div class="mt-6">
        <x-common.component-card title="Riwayat Terakhir">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                @forelse ($riwayat as $item)
                    <div class="flex items-center justify-between px-4 py-3 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                        <div>
                            <p class="text-sm text-gray-800 dark:text-white/90 m-0">{{ $item->kode_permintaan }}</p>
                            <p class="text-xs text-gray-400 m-0">{{ $item->requestedBy->nama_karyawan ?? '-' }}</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full {{ $item->status_permintaan == 'approved' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-error-500' }}">
                            {{ ucfirst($item->status_permintaan) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-400">Belum ada riwayat.</div>
                @endforelse
            </div>
        </x-common.component-card>
    </div>
</div>
@endsection
