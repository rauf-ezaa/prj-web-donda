@extends('layouts.app')
@php $currentPageTitle = 'Mulai Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-xl mx-auto">
    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-1">Mulai Stok Opname</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Pilih periode yang akan diopname. Sistem akan mengambil snapshot stok saat ini sebagai pembanding.
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    @if($periodes->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-400 dark:border-gray-700">
            Tidak ada periode aktif yang tersedia untuk diopname. Kemungkinan semua periode aktif sudah punya opname yang sedang berjalan, atau semua periode sudah terkunci.
        </div>
    @else
        <form method="POST" action="{{ route('admin.stok-opname.start') }}">
            @csrf
            <div class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Periode <span class="text-error-500">*</span>
                </label>
                <select name="periode_id"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">Pilih Periode</option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <x-ui.button size="md" variant="primary" type="submit">Mulai Opname</x-ui.button>
                <a href="{{ route('admin.stok-opname.index') }}">
                    <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
                </a>
            </div>
        </form>
    @endif
</div>
@endsection
