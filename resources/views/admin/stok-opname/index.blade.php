@extends('layouts.app')
@php $currentPageTitle = 'Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Stok Opname</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pencatatan stok opname yang pernah kamu buat.</p>
        </div>
        <a href="{{ route('admin.stok-opname.create') }}">
            <x-ui.button size="md" variant="primary">+ Mulai Opname Baru</x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">No. BAST</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($stokOpnames as $index => $so)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $stokOpnames->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $so->periode->nama }}</td>
                        <td class="px-4 py-3">{{ $so->no_bast ?: '-' }}</td>
                        <td class="px-4 py-3">
                            @if($so->status === 'dibatalkan_spv')
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs text-amber-600 dark:bg-amber-500/15 dark:text-amber-400">
                                    {{ $so->status_label }}
                                </span>
                            @else
                                <span class="text-xs">{{ $so->status_label }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if(in_array($so->status, ['draft', 'dibatalkan_spv']))
                                <a href="{{ route('admin.stok-opname.edit', $so->id) }}">
                                    <x-ui.button size="sm" variant="primary">Lanjutkan</x-ui.button>
                                </a>
                            @else
                                <a href="{{ route('admin.stok-opname.show', $so->id) }}">
                                    <x-ui.button size="sm" variant="secondary">Detail</x-ui.button>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data stok opname.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $stokOpnames->links() }}</div>
</div>
@endsection
