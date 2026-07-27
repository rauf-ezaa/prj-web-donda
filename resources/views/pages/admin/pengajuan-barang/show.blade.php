@extends('layouts.app')
@php
    $currentPageTitle = 'Detail Pengajuan';
@endphp
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Detail Pengajuan</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $pengajuan->kode_pengajuan }}</span>
    </div>

    <span class="inline-block text-xs px-2.5 py-1 rounded-full mb-5
        @if($pengajuan->status === 'approved') bg-green-50 text-green-700
        @elseif($pengajuan->status === 'rejected') bg-red-50 text-error-500
        @else bg-amber-50 text-amber-600
        @endif">
        {{ ucfirst($pengajuan->status) }}
    </span>

    <x-common.component-card title="Informasi Pengajuan">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Alasan Pengajuan</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->alasan_pengajuan }}</p>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Diajukan oleh</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->requestedBy->nama_karyawan ?? '-' }}</p>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal diajukan</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->created_at->translatedFormat('d F Y, H:i') }}</p>

        @if (in_array($pengajuan->status, ['approved', 'rejected']))
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                {{ $pengajuan->status === 'approved' ? 'Disetujui oleh' : 'Ditolak oleh' }}
            </p>
            <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->approvedBy->nama_karyawan ?? '-' }}</p>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal diproses</p>
            <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->approved_at?->translatedFormat('d F Y, H:i') }}</p>
        @endif

        @if ($pengajuan->catatan_approval)
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Catatan</p>
            <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->catatan_approval }}</p>
        @endif

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mt-4">
            @foreach ($pengajuan->details as $detail)
                <div class="flex items-center px-3.5 py-2.5 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div class="flex-1">
											<p class="text-sm m-0 text-gray-800 dark:text-white/90">{{ $detail->nama_barang_diajukan }}</p>

                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        Jumlah Barang Diajukan {{ $detail->jumlah_diajukan }}
                    </span>
                </div>
            @endforeach
        </div>


<div class="flex gap-2 mt-4">
    @if ($pengajuan->is_accessible)
        <a href="{{ route('pengajuan.draft', $pengajuan->id) }}">
            <x-ui.button size="sm" variant="secondary">Edit Barang</x-ui.button>
        </a>
        <form action="{{ route('pengajuan.batalkan', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pengajuan ini?')">
            @csrf
            <x-ui.button size="sm" variant="secondary" type="submit">Batalkan Pengajuan</x-ui.button>
        </form>
    @endif

    <a href="{{ route('pengajuan.index') }}">
        <x-ui.button size="sm" variant="secondary">Kembali</x-ui.button>
    </a>
</div>


		</x-common.component-card>



@endsection
