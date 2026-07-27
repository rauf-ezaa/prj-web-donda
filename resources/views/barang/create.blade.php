@extends('layouts.app')
@php $currentPageTitle = 'Tambah Barang'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-2xl mx-auto">
    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-6">Tambah Barang</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('data-barang.store') }}">
			@csrf
			@include('barang._form', ['kibList' => $kibList])
        <div class="mt-6 flex gap-2">
            <x-ui.button size="md" variant="primary" type="submit">Simpan</x-ui.button>
            <a href="{{ route('data-barang.index') }}">
                <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
            </a>
        </div>
    </form>
</div>
@endsection
