@extends('layouts.app')
@php $currentPageTitle = 'Data Pembelian'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Pembelian Barang</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat transaksi barang masuk.</p>
        </div>
        <a href="{{ route('pembelian.create') }}">
            <x-ui.button size="md" variant="primary">+ Input Pembelian</x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">No. Transaksi</th>
                    <th class="px-4 py-3">Supplier</th>
                    <th class="px-4 py-3">Tanggal Diterima</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($pembelians as $index => $p)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $pembelians->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $p->no_transaksi }}</td>
                        <td class="px-4 py-3">{{ $p->nama_supplier }}</td>
                        <td class="px-4 py-3">{{ $p->tanggal_diterima->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs">{{ $p->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pembelian.show', $p->id) }}">
                                <x-ui.button size="sm" variant="secondary">Detail</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pembelians->links() }}</div>
</div>
@endsection
