@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Verifikasi Stok Opname</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Daftar stok opname yang menunggu keputusan kamu.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">No. BAST</th>
                    <th class="px-4 py-3">Diajukan Oleh</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($stokOpnames as $index => $so)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $stokOpnames->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $so->periode->nama }}</td>
                        <td class="px-4 py-3">{{ $so->no_bast }}</td>
                        <td class="px-4 py-3">{{ $so->dibuatOleh->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('spv.stok-opname.show', $so->id) }}">
                                <x-ui.button size="sm" variant="primary">Review</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada opname yang perlu diverifikasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $stokOpnames->links() }}</div>
</div>
@endsection
