@extends('layouts.app')
@php $currentPageTitle = 'Mulai Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-xl mx-auto">
    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-1">Mulai Stok Opname</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Pilih bulan yang akan diopname. Stok sistem akan dihitung ulang dari Saldo Awal dan akumulasi transaksi hingga akhir bulan tersebut.
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    @if(!$tanggalMinimal)
        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-400 dark:border-gray-700">
            Belum ada Saldo Awal yang diverifikasi. Stok Opname tidak dapat dilakukan sebelum ada Saldo Awal.
        </div>
    @else
        <p class="mb-4 text-xs text-gray-400 dark:text-gray-500">
            Opname tidak dapat dilakukan untuk bulan sebelum {{ $tanggalMinimal->translatedFormat('F Y') }}.
        </p>

        <form method="POST" action="{{ route('admin.stok-opname.start') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bulan</label>
                    <select name="bulan" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @foreach(range(1, 12) as $b)
                            <option value="{{ $b }}" @selected(now()->month == $b)>{{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tahun</label>
                    <input type="number" name="tahun" value="{{ now()->year }}" min="2020" max="2100"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>
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
