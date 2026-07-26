{{-- resources/views/pages/dashboard/pengguna.blade.php --}}
@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Dashboard Pengguna" />

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Selamat Datang</h4>
            <p class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ auth()->user()->nama_karyawan }}
            </p>
        </div>


				 <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</h4>
            <p class="mt-2 text-lg font-semibold text-gray-800 dark:text-white/90">
               {{ $data->jabatan }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengajuan Saya</h4>
            <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">0</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Peminjaman Aktif</h4>
            <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90">0</p>
        </div>
    </div>
@endsection
