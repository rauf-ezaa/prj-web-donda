@extends('layouts.app')
@php $currentPageTitle = 'Saldo Awal'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Saldo Awal Barang</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pencatatan stok awal sebelum sistem digunakan.</p>
        </div>
        <a href="{{ route('saldo-awal.create') }}">
            <x-ui.button size="md" variant="primary">+ Input Saldo Awal</x-ui.button>
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
                    <th class="px-4 py-3">Tanggal Pencatatan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($saldoAwals as $index => $s)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $saldoAwals->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $s->no_transaksi }}</td>
                        <td class="px-4 py-3">{{ $s->tanggal_pencatatan->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs">{{ $s->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('saldo-awal.show', $s->id) }}">
                                <x-ui.button size="sm" variant="secondary">Detail</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data saldo awal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $saldoAwals->links() }}</div>
</div>
@endsection
