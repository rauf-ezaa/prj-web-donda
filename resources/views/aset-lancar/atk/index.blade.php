@extends('layouts.app')
@php $currentPageTitle = 'Data Barang'; @endphp
@section('content')

<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Data Barang ATK</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400"></p>
        </div>

    </div>

    <!-- Filter -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('admin.atk') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama barang..."
                    class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>

            <x-ui.button size="md" variant="primary" type="submit">Filter</x-ui.button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama Barang</th>
                    <th class="px-4 py-3">Merk/Spesifikasi</th>
                    <th class="px-4 py-3">Klasifikasi</th>
                    <th class="px-4 py-3">Stok Tersedia</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($barangs as $index => $b)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $barangs->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $b->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $b->description ?: '-' }}</td>
                        <td class="px-4 py-3">{{ $b->kib->klasifikasi }}</td>
                        <td class="px-4 py-3">{{ $b->stok_tersedia }} {{ $b->satuan }}</td>

                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $barangs->links() }}</div>
</div>
@endsection
