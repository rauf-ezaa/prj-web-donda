@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Pembelian'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-3xl mx-auto">
    <a href="{{ route('spv.pembelian.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
        ← Kembali
    </a>

    <div class="flex justify-between items-baseline mb-1">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">{{ $pembelian->no_transaksi }}</h1>
        <span class="text-xs text-amber-600">{{ $pembelian->status_label }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Diajukan oleh {{ $pembelian->dibuatOleh->name }} · {{ $pembelian->created_at->format('d M Y, H:i') }}
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="mb-6 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Supplier</p>
            <p class="text-gray-800 dark:text-white/90">{{ $pembelian->nama_supplier }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-0.5">Tanggal Diterima</p>
            <p class="text-gray-800 dark:text-white/90">{{ $pembelian->tanggal_diterima->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Qty</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Stok Saat Ini</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($pembelian->items as $index => $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $item->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $item->qty }} {{ $item->barang->satuan }}</td>
                        <td class="px-4 py-3">{{ $item->deskripsi ?: '-' }}</td>
                        <td class="px-4 py-3">{{ $item->barang->stok_tersedia }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pembelian->catatan)
        <div class="mb-6 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/[0.02] dark:text-gray-400">
            <strong>Catatan:</strong> {{ $pembelian->catatan }}
        </div>
    @endif

    <div class="mb-6 rounded-lg bg-blue-50 p-3 text-xs text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
        Setelah verifikasi, stok barang akan langsung bertambah sesuai qty di atas.
    </div>

    <div class="flex gap-2">
        <form action="{{ route('spv.pembelian.verify', $pembelian->id) }}" method="POST"
              onsubmit="return confirm('Yakin verifikasi? Stok akan langsung diperbarui.')">
            @csrf
            <x-ui.button size="md" variant="primary" type="submit">Verifikasi & Tambah Stok</x-ui.button>
        </form>
        <form action="{{ route('spv.pembelian.reject', $pembelian->id) }}" method="POST"
              onsubmit="return confirm('Yakin tolak pembelian ini?')">
            @csrf
            <input type="hidden" name="alasan" value="Ditolak oleh supervisor">
            <x-ui.button size="md" variant="secondary" type="submit">Tolak</x-ui.button>
        </form>
    </div>
</div>
@endsection
