@extends('layouts.app')
@php $currentPageTitle = 'Statistik Barang'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Statistik Barang Paling Sering Digunakan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Diurutkan berdasarkan total pemakaian dari seluruh modul, dari yang tertinggi.</p>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3 text-center">Peminjaman</th>
                    <th class="px-4 py-3 text-center">Permintaan</th>
                    <th class="px-4 py-3 text-center">Pembelian</th>
                    <th class="px-4 py-3 text-center">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($statistik as $index => $s)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium">{{ $s['nama_barang'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $s['peminjaman'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $s['permintaan'] }}</td>
                        <td class="px-4 py-3 text-center">{{ $s['pembelian'] }}</td>
                        <td class="px-4 py-3 text-center font-semibold">{{ $s['total_keseluruhan'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
