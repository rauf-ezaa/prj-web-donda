@extends('layouts.app')
@php $currentPageTitle = 'Detail Peminjaman'; @endphp
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Detail Peminjaman</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $peminjaman->kode_peminjaman }}</span>
    </div>

    <span class="inline-block text-xs px-2.5 py-1 rounded-full mb-5
        @if($peminjaman->status === 'dikembalikan') bg-gray-100 text-gray-600
        @elseif($peminjaman->status === 'dipinjam') bg-blue-50 text-blue-700
        @elseif($peminjaman->status === 'rejected') bg-red-50 text-error-500
        @else bg-amber-50 text-amber-600
        @endif">
        {{ ucfirst(str_replace('_', ' ', $peminjaman->status)) }}
    </span>

    @if ($peminjaman->is_terlambat)
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            Peminjaman ini sudah melewati batas waktu wajib kembali ({{ $peminjaman->tanggal_wajib_kembali->translatedFormat('d F Y') }})
        </div>
    @endif

    <x-common.component-card title="Informasi Peminjaman">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Keperluan</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $peminjaman->keperluan }}</p>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Pinjam</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $peminjaman->tanggal_pinjam->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Wajib Kembali</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $peminjaman->tanggal_wajib_kembali->translatedFormat('d F Y') }}</p>
            </div>
        </div>


        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-5">
            @foreach ($peminjaman->details as $detail)
                <div class="flex items-center justify-between px-3.5 py-2.5 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div>
                        <p class="text-sm m-0 text-gray-800 dark:text-white/90">{{ $detail->barang->nama_barang }}</p>
                        <p class="text-xs text-gray-400 m-0">Pinjam: {{ $detail->qty_pinjam }}</p>
                    </div>
                    @if ($detail->kondisi_kembali)
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $detail->kondisi_kembali === 'baik' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-error-500' }}">
                            {{ str_replace('_', ' ', ucfirst($detail->kondisi_kembali)) }}
                        </span>
                    @endif
                </div>
            @endforeach

        </div>

				<div class="flex gap-2 mt-4">
    @if ($peminjaman->is_editable)
        <a href="{{ route('peminjaman.draft', $peminjaman->id) }}">
            <x-ui.button size="sm" variant="secondary">Edit Barang</x-ui.button>
        </a>
        <form action="{{ route('peminjaman.batalkan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan peminjaman ini?')">
            @csrf
            <x-ui.button size="sm" variant="secondary" type="submit">Batalkan peminjaman</x-ui.button>
        </form>
    @endif

    <a href="{{ route('peminjaman.index') }}">
        <x-ui.button size="sm" variant="secondary">Kembali</x-ui.button>
    </a>
</div>

    </x-common.component-card>
</div>

@endsection
