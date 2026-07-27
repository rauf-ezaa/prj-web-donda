@extends('layouts.app')
@php $currentPageTitle = 'Detail Pembelian'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-3xl mx-auto">
    <a href="{{ route('pembelian.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
        ← Kembali
    </a>

    <div class="flex justify-between items-baseline mb-1">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">{{ $pembelian->no_transaksi }}</h1>
        <span class="text-xs text-amber-600">{{ $pembelian->status_label }}</span>
				@if($pembelian->status === 'menunggu_verifikasi_spv')
				<a href="{{ route('pembelian.edit', $pembelian->id) }}" class="mb-4 inline-block">
						<x-ui.button size="sm" variant="secondary">✏ Edit Transaksi</x-ui.button>
				</a>
@endif
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Diajukan oleh {{ $pembelian->dibuatOleh->nama_karyawan }} · {{ $pembelian->created_at->format('d M Y, H:i') }}
    </p>

    <div class="mb-6 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Supplier</p>
            <p class="text-gray-800 dark:text-white/90">{{ $pembelian->nama_supplier }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Tanggal Diterima</p>
            <p class="text-gray-800 dark:text-white/90">{{ $pembelian->tanggal_diterima->translatedFormat('d F Y') }}</p>
        </div>
        @if($pembelian->status === 'selesai')
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Diverifikasi Oleh</p>
                <p class="text-gray-800 dark:text-white/90">{{ $pembelian->diverifikasiOleh->name }}</p>
            </div>
        @endif
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Qty</th>
                    <th class="px-4 py-3">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($pembelian->items as $index => $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $item->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $item->qty }} {{ $item->barang->satuan }}</td>
                        <td class="px-4 py-3">{{ $item->deskripsi ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pembelian->catatan)
        <div class="mb-6 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/[0.02] dark:text-gray-400">
            <strong>Catatan:</strong> {{ $pembelian->catatan }}
        </div>
    @endif

    @if($pembelian->status === 'ditolak' && $pembelian->alasan_tolak)
        <div class="mb-6 rounded-lg bg-red-50 p-3 text-sm text-error-500 dark:bg-red-500/10">
            <strong>Alasan ditolak:</strong> {{ $pembelian->alasan_tolak }}
        </div>
    @endif
</div>
@endsection
