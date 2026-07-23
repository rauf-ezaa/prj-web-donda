@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Persediaan'; @endphp
@section('content')

<div class="max-w-3xl mx-auto">
    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-5">Verifikasi Barang Masuk</h3>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <x-common.component-card title="Menunggu Persetujuan">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            @forelse ($persediaan as $item)
                <a href="{{ route('spv.persediaan.show', $item->id) }}"
                   class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div>
                        <p class="text-sm text-gray-800 dark:text-white/90 m-0">{{ $item->barang->nama_barang ?? '-' }}</p>
                        <p class="text-xs text-gray-400 m-0">
                            Qty: {{ $item->qty }} · Dana: {{ strtoupper($item->asal_dana) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-700 dark:text-gray-300 m-0">
                            Rp{{ number_format($item->harga_total, 0, ',', '.') }}
                        </p>
                        <span class="text-xs text-gray-400">{{ $item->tanggal_masuk }}</span>
                    </div>
                </a>
            @empty
                <div class="p-6 text-center text-sm text-gray-400">
                    Tidak ada barang masuk yang menunggu persetujuan.
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
                            <p class="text-sm text-gray-800 dark:text-white/90 m-0">{{ $item->barang->nama_barang ?? '-' }}</p>
                            <p class="text-xs text-gray-400 m-0">Qty: {{ $item->qty }}</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full {{ $item->approval_status === 'diterima' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-error-500' }}">
                            {{ ucfirst($item->approval_status) }}
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
