@extends('layouts.app')
@php
    use Illuminate\Support\HtmlString;

    // Page title
    $currentPageTitle = 'Buttons';

    // Define BoxIcon once as an HtmlString (so it won’t get escaped)
    $BoxIcon = new HtmlString('
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M9.77692 3.24224C9.91768 3.17186 10.0834 3.17186 10.2241 3.24224L15.3713 5.81573L10.3359 8.33331C10.1248 8.43888 9.87626 8.43888 9.66512 8.33331L4.6298 5.81573L9.77692 3.24224ZM3.70264 7.0292V13.4124C3.70264 13.6018 3.80964 13.775 3.97903 13.8597L9.25016 16.4952L9.25016 9.7837C9.16327 9.75296 9.07782 9.71671 8.99432 9.67496L3.70264 7.0292ZM10.7502 16.4955V9.78396C10.8373 9.75316 10.923 9.71683 11.0067 9.67496L16.2984 7.0292V13.4124C16.2984 13.6018 16.1914 13.775 16.022 13.8597L10.7502 16.4955ZM9.41463 17.4831L9.10612 18.1002C9.66916 18.3817 10.3319 18.3817 10.8949 18.1002L16.6928 15.2013C17.3704 14.8625 17.7984 14.17 17.7984 13.4124V6.58831C17.7984 5.83076 17.3704 5.13823 16.6928 4.79945L10.8949 1.90059C10.3319 1.61908 9.66916 1.61907 9.10612 1.90059L9.44152 2.57141L9.10612 1.90059L3.30823 4.79945C2.63065 5.13823 2.20264 5.83076 2.20264 6.58831V13.4124C2.20264 14.17 2.63065 14.8625 3.30823 15.2013L9.10612 18.1002L9.41463 17.4831Z"
                fill="currentColor"
            />
        </svg>
    ');
@endphp
@section('content')

 <x-common.page-breadcrumb pageTitle="{{ $permintaan->kode_permintaan }}"
    parentTitle="Data Permintaan"
    :parentRoute="route('permintaan.index')" />

<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Detail Permintaan</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $permintaan->kode_permintaan }}</span>
    </div>

    <span class="inline-block text-xs px-2.5 py-1 rounded-full mb-5
        @if($permintaan->status === 'approved') bg-green-50 text-green-700
        @elseif($permintaan->status === 'rejected') bg-red-50 text-error-500
        @else bg-amber-50 text-amber-600
        @endif">
        {{ ucfirst($permintaan->status_permintaan) }}
    </span>

    <x-common.component-card title="Informasi Permintaan">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Keperluan</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $permintaan->keperluan }}</p>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Diajukan oleh</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $permintaan->requestedBy->nama_karyawan ?? '-' }}</p>

        @if ($permintaan->status !== 'draft' && $permintaan->status !== 'pending')
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Disetujui/ditolak oleh</p>
            <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $permintaan->approvedBy->nama_karyawan ?? '-' }}</p>
        @endif

        @if ($permintaan->catatan_approval)
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Catatan</p>
            <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $permintaan->catatan_approval }}</p>
        @endif

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mt-4">
            @foreach ($permintaan->details as $detail)
                <div class="flex items-center px-3.5 py-2.5 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div class="flex-1">
                        <p class="text-sm m-0 text-gray-800 dark:text-white/90">{{ $detail->barang->nama_barang }}</p>
                        <p class="text-xs text-gray-400 m-0">Diminta: {{ $detail->jumlah_diminta }}</p>
                    </div>
                    @if ($detail->jumlah_disetujui !== null)
                        <span class="text-xs text-green-600">Disetujui: {{ $detail->jumlah_disetujui }}</span>
                    @endif
                </div>
            @endforeach
        </div>
				<div class="flex gap-2 mt-4">
					 @if ($permintaan->is_editable)
        <a href="{{ route('permintaan.draft', $permintaan->id) }}">
            <x-ui.button size="sm" variant="secondary">Edit Barang</x-ui.button>
        </a>
        <form action="{{ route('permintaan.batalkan', $permintaan->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan permintaan ini?')">
            @csrf
            <x-ui.button size="sm" variant="secondary" type="submit">Batalkan permintaan</x-ui.button>
        </form>
    @endif

    <a href="{{ route('permintaan.index') }}">
        <x-ui.button size="sm" variant="secondary">Kembali</x-ui.button>
    </a>
</div>

    </x-common.component-card>
</div>
@endsection
