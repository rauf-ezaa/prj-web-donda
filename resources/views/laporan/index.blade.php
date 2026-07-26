@extends('layouts.app')
@php $currentPageTitle = 'Laporan'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Laporan</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih modul untuk melihat laporan lengkap dengan filter.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($ringkasan as $key => $r)
            <a href="{{ route('laporan.modul', $key) }}"
               class="rounded-xl border border-gray-200 bg-white p-5 transition hover:border-brand-300 hover:shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $r['label'] }}</p>
                <p class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $r['total'] }}</p>
                <p class="text-xs text-gray-400 mt-1">total transaksi</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
