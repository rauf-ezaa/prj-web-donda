@extends('layouts.app')
@php $currentPageTitle = 'Buat Periode Baru'; @endphp
@section('content')
<div class="p-4 md:p-6 max-w-xl mx-auto">
    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-6">Buat Periode Baru</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('spv.periode.store') }}">
        @csrf

        <div class="grid grid-cols-2 gap-3 mb-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Semester <span class="text-error-500">*</span>
                </label>
                <select name="semester"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="ganjil" @selected(old('semester') == 'ganjil')>Ganjil</option>
                    <option value="genap" @selected(old('semester') == 'genap')>Genap</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Tahun <span class="text-error-500">*</span>
                </label>
                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="2020" max="2100"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
        </div>

        <div class="flex gap-2">
            <x-ui.button size="md" variant="primary" type="submit">Buat Periode</x-ui.button>
            <a href="{{ route('spv.periode.index') }}">
                <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
            </a>
        </div>
    </form>
</div>
@endsection
