{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="flex min-h-[70vh] flex-col items-center justify-center px-4 text-center">
    <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-50 dark:bg-red-500/10">
        <i class="ti ti-lock text-4xl text-error-500"></i>
    </div>
    <h1 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Akses Ditolak</h1>
    <p class="mb-6 max-w-md text-sm text-gray-500 dark:text-gray-400">
        Kamu tidak memiliki izin untuk mengakses halaman ini. Kalau menurutmu ini salah, hubungi admin sistem.
    </p>
    <a href="{{ route(auth()->user()?->dashboardRoute() ?? 'login') }}">
        <x-ui.button size="md" variant="primary">Kembali ke Dashboard</x-ui.button>
    </a>
</div>
@endsection
