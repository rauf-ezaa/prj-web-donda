@extends('layouts.app')
@php $currentPageTitle = 'Laporan ' . $modulLabel; @endphp
@section('content')
<div class="p-4 md:p-6">
    <a href="{{ route('laporan.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">← Kembali ke Laporan</a>

    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Laporan {{ $modulLabel }}</h1>
    </div>

    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('laporan.modul', $modul) }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="min-w-[160px]">
                <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ $filter['dari_tanggal'] ?? '' }}"
                    class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
            <div class="min-w-[160px]">
                <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" value="{{ $filter['sampai_tanggal'] ?? '' }}"
                    class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
            <x-ui.button size="md" variant="primary" type="submit">Filter</x-ui.button>
            @if(array_filter($filter))
                <a href="{{ route('laporan.modul', $modul) }}">
                    <x-ui.button size="md" variant="secondary" type="button">Reset</x-ui.button>
                </a>
            @endif
            <a href="{{ route('laporan.modul.export-pdf', array_merge(['modul' => $modul], $filter)) }}" target="_blank">
                <x-ui.button size="md" variant="secondary" type="button">📄 Export Semua (List)</x-ui.button>
            </a>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Kode Transaksi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal Dibuat</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($data as $index => $row)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $data->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $row->{$kolomKode} ?? "#{$row->id}" }}</td>
                        <td class="px-4 py-3">{{ str_replace('_', ' ', ucfirst($row->status ?? $row->status_permintaan ?? $row->status_pemintaan ?? '-')) }}</td>
                        <td class="px-4 py-3">{{ $row->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('laporan.modul.detail-pdf', [$modul, $row->id]) }}" target="_blank">
                                <x-ui.button size="sm" variant="secondary">📄 Detail PDF</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $data->links() }}</div>
</div>
@endsection
