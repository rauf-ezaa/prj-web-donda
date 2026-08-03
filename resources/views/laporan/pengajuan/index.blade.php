@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
            Analisis Kebutuhan Pengadaan
        </h2>

        <p class="text-sm text-gray-500">
            Rekapitulasi usulan barang berdasarkan riwayat pengajuan.
        </p>
    </div>

    {{-- FILTER --}}
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

        <form method="GET">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Tanggal Awal
                    </label>

                    <input
                        type="text"
                        id="start_date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="form-input w-full">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Tanggal Akhir
                    </label>

                    <input
                        type="text"
                        id="end_date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="form-input w-full">
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="inline-flex rounded-lg bg-brand-500 px-4 py-3 text-white hover:bg-brand-600">

                        Filter

                    </button>
                </div>

                <div class="flex items-end">
                    <a
                        href="{{ route('laporan.pengajuan') }}"
                        class="inline-flex rounded-lg border border-gray-300 px-4 py-3">

                        Reset

                    </a>
                </div>

            </div>

        </form>

    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500">Total Pengajuan</p>
            <h3 class="mt-2 text-3xl font-bold">
                {{ number_format($summary['total_pengajuan']) }}
            </h3>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500">Barang Unik</p>
            <h3 class="mt-2 text-3xl font-bold">
                {{ number_format($summary['total_item_unik']) }}
            </h3>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500">Total Qty</p>
            <h3 class="mt-2 text-3xl font-bold">
                {{ number_format($summary['total_qty']) }}
            </h3>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500">Barang Teratas</p>

            <h3 class="mt-2 text-lg font-semibold">
                {{ $barangTeratas?->nama_barang_diajukan ?? '-' }}
            </h3>

            <span class="text-sm text-gray-500">
                {{ $barangTeratas?->frekuensi ?? 0 }} kali diajukan
            </span>
        </div>

    </div>

    {{-- TOP BARANG --}}
    <div
        x-data="{ open: true }"
        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div
            @click="open = !open"
            class="flex cursor-pointer items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">

            <h3 class="font-semibold">
                Top Barang Yang Paling Banyak Diajukan
            </h3>

            <span x-text="open ? '−' : '+'"></span>

        </div>

        <div x-show="open">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead>

                        <tr class="border-b border-gray-200 dark:border-gray-800">

                            <th class="px-5 py-3 text-left">Ranking</th>
                            <th class="px-5 py-3 text-left">Nama Barang</th>
                            <th class="px-5 py-3 text-center">Frekuensi</th>
                            <th class="px-5 py-3 text-center">Total Qty</th>
                            <th class="px-5 py-3 text-center">Terakhir Diajukan</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($topBarang as $item)

                            <tr class="border-b border-gray-100 dark:border-gray-800">

                                <td class="px-5 py-4">
                                    #{{ $loop->iteration }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ $item->nama_barang_diajukan }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ number_format($item->frekuensi) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ number_format($item->total_qty) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($item->terakhir_diajukan)->format('d M Y') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- RIWAYAT --}}
    <div
        x-data="{ open: false }"
        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div
            @click="open = !open"
            class="flex cursor-pointer items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">

            <h3 class="font-semibold">
                Riwayat Pengajuan
            </h3>

            <span x-text="open ? '−' : '+'"></span>

        </div>

        <div x-show="open">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead>

                        <tr class="border-b border-gray-200 dark:border-gray-800">

                            <th class="px-5 py-3 text-left">Kode</th>
                            <th class="px-5 py-3 text-left">Pemohon</th>
                            <th class="px-5 py-3 text-center">Jumlah Item</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center">Tanggal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($riwayat as $item)

                            <tr class="border-b border-gray-100 dark:border-gray-800">

                                <td class="px-5 py-4">
                                    {{ $item->kode_pengajuan }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $item->requestedBy?->nama_karyawan ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ $item->details_count }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ ucfirst($item->status) }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    {{ $item->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="p-5">
                {{ $riwayat->links() }}
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>

flatpickr("#start_date", {
    dateFormat: "Y-m-d",
    allowInput: true
});

flatpickr("#end_date", {
    dateFormat: "Y-m-d",
    allowInput: true
});

</script>
@endpush
