.@extends('layouts.app')
@php $currentPageTitle = 'Rekap Saldo Awal per Barang'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Rekap Saldo Awal</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total qty saldo awal per barang, digabung dari seluruh sesi yang sudah diverifikasi.
            </p>
        </div>
        <a href="{{ route('admin.saldo-awal.index') }}">
            <x-ui.button size="md" variant="secondary">← Riwayat Transaksi</x-ui.button>
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Total Qty Saldo Awal</th>
                    <th class="px-4 py-3">Jumlah Sesi Input</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($rekap as $index => $r)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium">{{ $r->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $r->total_qty }} {{ $r->barang->satuan }}</td>
                        <td class="px-4 py-3">
                            @if($r->jumlah_sesi > 1)
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                                    {{ $r->jumlah_sesi }}x diinput
                                </span>
                            @else
                                1x
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.saldo-awal.rincian', $r->barang_id) }}">
                                <x-ui.button size="sm" variant="secondary">Lihat Rincian</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data saldo awal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
