{{-- admin/saldo-awal/rincian.blade.php --}}
@extends('layouts.app')
@php $currentPageTitle = 'Rincian Saldo Awal'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-2xl mx-auto">
    <a href="{{ route('admin.saldo-awal.rekap') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
        ← Kembali ke Rekap
    </a>

    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-1">
        Rincian Saldo Awal — {{ $barang->nama_barang }}
    </h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Total: {{ $items->sum('qty') }} {{ $barang->satuan }}, dari {{ $items->count() }} kali input.
    </p>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No. Transaksi</th>
                    <th class="px-4 py-3">Qty</th>
                    <th class="px-4 py-3">Diinput Oleh</th>
                    <th class="px-4 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($items as $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $item->saldoAwal->no_transaksi }}</td>
                        <td class="px-4 py-3">{{ $item->qty }}</td>
                        <td class="px-4 py-3">{{ $item->saldoAwal->dibuatOleh->name }}</td>
                        <td class="px-4 py-3">{{ $item->saldoAwal->tanggal_pencatatan->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
