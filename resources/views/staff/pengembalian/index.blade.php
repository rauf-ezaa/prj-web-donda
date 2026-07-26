@extends('layouts.app')
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
                Pengembalian Barang
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pilih peminjaman yang ingin diproses pengembaliannya.
            </p>
        </div>
        <a href="{{ route('pengembalian.riwayat') }}">
            <x-ui.button size="md" variant="secondary">Riwayat Pengembalian</x-ui.button>
        </a>
    </div>

    <div class="mb-5">
        <form method="GET" action="{{ route('pengembalian.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode peminjaman..."
                class="h-10 w-full max-w-xs rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            <x-ui.button size="md" variant="primary" type="submit">Cari</x-ui.button>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Tanggal Pinjam</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Sisa Belum Kembali</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($peminjaman as $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3 font-medium">{{ $item->kode_peminjaman }}</td>
                        <td class="px-4 py-3">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <x-ui.badge :status="$item->status" />
                        </td>
                        <td class="px-4 py-3">{{ $item->total_sisa }} item</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pengembalian.create', $item->id) }}">
                                <x-ui.button size="sm" variant="primary">Proses Pengembalian</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            Tidak ada peminjaman yang perlu dikembalikan saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
