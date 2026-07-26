@extends('layouts.app')
@php $currentPageTitle = 'Detail Verifikasi Pengembalian'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-3xl mx-auto">

    <a href="{{ route('spv.pengembalian.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
        ← Kembali ke Daftar Verifikasi
    </a>

    <div class="flex justify-between items-baseline mb-1">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
            Verifikasi Pengembalian — {{ $pengembalian->peminjaman->kode_peminjaman }}
        </h1>
        <span class="text-xs text-amber-600">{{ $pengembalian->status_label }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Pengembalian diajukan oleh {{ $pengembalian->staff->name }} · {{ $pengembalian->tanggal_pengembalian->format('d M Y, H:i') }}
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Data Peminjaman Asal -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.02]">
        <p class="mb-3 text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-gray-500">
            Data Peminjaman
        </p>
        <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Peminjam</p>
                <p class="text-gray-800 dark:text-white/90">{{ $pengembalian->peminjaman->requestedBy->nama_karyawan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Tanggal Pinjam</p>
                <p class="text-gray-800 dark:text-white/90">{{ $pengembalian->peminjaman->tanggal_pinjam->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Wajib Kembali</p>
                <p class="text-gray-800 dark:text-white/90">{{ $pengembalian->peminjaman->tanggal_wajib_kembali->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Keperluan</p>
                <p class="text-gray-800 dark:text-white/90">{{ $pengembalian->peminjaman->keperluan ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Detail Barang yang Dikembalikan -->
    <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-400">
        Barang yang Diajukan Kembali
    </p>
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3 w-10">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Qty Pinjam</th>
                    <th class="px-4 py-3 text-center">Baik</th>
                    <th class="px-4 py-3 text-center">Rusak Ringan</th>
                    <th class="px-4 py-3 text-center">Rusak Berat</th>
                    <th class="px-4 py-3 text-center">Hilang</th>
                    <th class="px-4 py-3 text-center">Habis Terpakai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($pengembalian->items as $index => $d)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $d->peminjamanItem->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $d->peminjamanItem->qty_pinjam }}</td>
                        <td class="px-4 py-3 text-center">{{ $d->qty_baik }}</td>
                        <td class="px-4 py-3 text-center">{{ $d->qty_rusak_ringan}}</td>
                        <td class="px-4 py-3 text-center">{{ $d->qty_rusak_berat}}</td>

                        <td class="px-4 py-3 text-center">
                            @if($d->qty_hilang > 0)
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs text-error-500 dark:bg-red-500/15 dark:text-red-400">
                                    {{ $d->qty_hilang }} — Hilang
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($d->qty_habis_terpakai > 0)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600 dark:bg-gray-500/15 dark:text-gray-400">
                                    {{ $d->qty_habis_terpakai }} — Habis Pakai
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pengembalian->catatan)
        <div class="mb-6 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/[0.02] dark:text-gray-400">
            <strong>Catatan dari staff:</strong> {{ $pengembalian->catatan }}
        </div>
    @endif

    <div class="flex gap-2">
        <form action="{{ route('spv.pengembalian.verify', $pengembalian->id) }}" method="POST"
              onsubmit="return confirm('Yakin verifikasi pengembalian ini ?')">
            @csrf
            <x-ui.button size="md" variant="primary" type="submit">Verifikasi</x-ui.button>
        </form>

        <form action="{{ route('spv.pengembalian.reject', $pengembalian->id) }}" method="POST"
              onsubmit="return confirm('Yakin tolak pengembalian ini?')">
            @csrf
            <input type="hidden" name="alasan" value="Ditolak oleh spv">
            <x-ui.button size="md" variant="secondary" type="submit">Tolak</x-ui.button>
        </form>
    </div>
</div>
@endsection
