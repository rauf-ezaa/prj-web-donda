{{-- resources/views/supervisor/stok-opname/show.blade.php --}}
@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-3xl mx-auto">
    <a href="{{ route('spv.stok-opname.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">← Kembali</a>

    <div class="flex justify-between items-baseline mb-1">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
            Stok Opname — {{ $stokOpname->periode->nama }}
        </h1>
        <span class="text-xs text-amber-600">{{ $stokOpname->status_label }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
        Diajukan oleh {{ $stokOpname->dibuatOleh->name }} · No. BAST: {{ $stokOpname->no_bast }} · {{ $stokOpname->tanggal_bast->translatedFormat('d F Y') }}
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    {{-- INI PREVIEW-NYA: tabel perbandingan stok sistem vs fisik, per barang --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Stok Sistem</th>
                    <th class="px-4 py-3">Stok Fisik</th>
                    <th class="px-4 py-3">Selisih</th>
                    <th class="px-4 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($stokOpname->items as $index => $item)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $item->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $item->stok_sistem }}</td>
                        <td class="px-4 py-3">{{ $item->stok_fisik }}</td>
                        <td class="px-4 py-3">
                            @if($item->selisih !== 0)
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs text-error-500 dark:bg-red-500/15 dark:text-red-400">
                                    {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
                                </span>
                            @else
                                <span class="text-gray-300 dark:text-gray-600">0</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $item->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($stokOpname->catatan)
        <div class="mb-6 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/[0.02] dark:text-gray-400">
            <strong>Catatan:</strong> {{ $stokOpname->catatan }}
        </div>
    @endif

    <div class="mb-6 rounded-lg bg-blue-50 p-3 text-xs text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
        Setelah verifikasi, stok barang akan langsung disesuaikan ke stok fisik. Sistem terkunci untuk transaksi baru sampai keputusan ini dibuat.
    </div>

    {{-- INI AKSI VERIFIKASI-NYA: 2 tombol di bawah --}}
    <div class="flex gap-2">
        <form action="{{ route('spv.stok-opname.verify', $stokOpname->id) }}" method="POST"
              onsubmit="return confirm('Yakin verifikasi? Stok akan disesuaikan.')">
            @csrf
            <x-ui.button size="md" variant="primary" type="submit">Verifikasi & Sesuaikan Stok</x-ui.button>
        </form>

        <form action="{{ route('spv.stok-opname.cancel', $stokOpname->id) }}" method="POST" x-data="{ show: false }">
            @csrf
            <div x-show="!show">
                <x-ui.button size="md" variant="secondary" type="button" @click="show = true">Batalkan (Minta Revisi)</x-ui.button>
            </div>
            <div x-show="show" x-cloak class="flex gap-2 items-center">
                <input type="text" name="catatan_cancel" placeholder="Alasan pembatalan..." required
                    class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white/90">
                <x-ui.button size="md" variant="secondary" type="submit">Kirim</x-ui.button>
            </div>
        </form>
    </div>
</div>
@endsection
