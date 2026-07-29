@extends('layouts.app')
@php $currentPageTitle = 'Detail Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-3xl mx-auto">
    <a href="{{ route('admin.stok-opname.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">← Kembali</a>
    <div class="flex justify-between items-baseline mb-1">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
            Stok Opname — {{ $stokOpname->nama_bulan }}
        </h1>
        <span class="text-xs text-amber-600">{{ $stokOpname->status_label }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        No. BAST: {{ $stokOpname->no_bast }} · {{ $stokOpname->tanggal_bast?->translatedFormat('d F Y') }}
    </p>

    @if($stokOpname->status === 'selesai')
        <div class="mb-6 flex flex-col gap-3 rounded-lg bg-success-50 border border-success-200 px-4 py-3 dark:bg-success-500/10 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-success-600 dark:text-success-400">
                Diverifikasi oleh {{ $stokOpname->diverifikasiOleh->name }} pada {{ $stokOpname->diverifikasi_at->format('d M Y, H:i') }}. Stok telah disesuaikan.
            </p>
            <a href="{{ route('admin.stok-opname.cetak-bast', $stokOpname->id) }}" target="_blank" class="shrink-0">
                <x-ui.button size="sm" variant="primary">🖨 Cetak BAST</x-ui.button>
            </a>
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Stok Sistem</th>
                    <th class="px-4 py-3">Stok Fisik</th>
                    <th class="px-4 py-3">Selisih</th>
                    <th class="px-4 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($stokOpname->items as $index => $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $item->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $item->stok_sistem }}</td>
                        <td class="px-4 py-3">{{ $item->stok_fisik ?? "tidak ada data"  }}</td>
                        <td class="px-4 py-3">
                            @if($item->selisih !== 0)
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs text-error-500 dark:bg-red-500/15 dark:text-red-400">
                                    {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-gray-600">0</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($stokOpname->catatan)
        <div class="mb-6 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/[0.02] dark:text-gray-400">
            <strong>Catatan:</strong> {{ $stokOpname->catatan }}
        </div>
    @endif
</div>
@endsection
