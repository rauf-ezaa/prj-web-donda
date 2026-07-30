@extends('layouts.app')
@php $currentPageTitle = 'Stok Opname'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Stok Opname</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat pencatatan stok opname yang pernah kamu buat.</p>
        </div>

				@if($isDraftOrTransactionExist)
					<a href="{{ route('admin.stok-opname.edit', $isDraftOrTransactionExist->id) }}">
							<x-ui.button size="md" variant="primary">+ Lanjutkan Draft Stok Opname </x-ui.button>
					</a>
				@else

					<a href="{{ route('admin.stok-opname.create') }}">
						<x-ui.button size="md" variant="primary">+ Mulai Opname Baru</x-ui.button>
					</a>
					@endif

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

    {{--
        Mapping label & warna status. Sesuaikan key di sini kalau nilai status
        di database/enum kamu berbeda dari asumsi ini.
    --}}
    @php
        $statusOptions = [
            'draft'          => 'Draft',
            'menunggu_verifikasi_spv'       => 'Diajukan',
            'selesai'      => 'Selesai',
            'dibatalkan_spv' => 'Dibatalkan Supervisor',
        ];

        $statusBadgeClass = [
            'draft'          => 'bg-yellow-100 text-yellow-600 dark:bg-white/5 dark:text-yellow-400',
            'menunggu_verifikasi_spv'       => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
            'selesai'      => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
            'ditolak'        => 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-400',
            'dibatalkan_spv' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400',
        ];
        $defaultBadgeClass = 'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400';
    @endphp

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.stok-opname.index') }}"
        class="mb-4 flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] sm:flex-row sm:items-end">
        <div class="w-full sm:w-56">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
            <select name="status"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') == $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <x-ui.button size="md" variant="primary" type="submit">Cari</x-ui.button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.stok-opname.index') }}">
                    <x-ui.button size="md" variant="secondary" type="button">Reset</x-ui.button>
                </a>
            @endif
        </div>
    </form>

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
                        <td class="px-4 py-3 font-medium">{{ $so->nama_bulan }}</td>
                        <td class="px-4 py-3">{{ $so->no_bast ?: '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs {{ $statusBadgeClass[$so->status] ?? $defaultBadgeClass }}">
                                {{ $so->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
													@if($so->status ==='draft')
                            <a href="{{ route('admin.stok-opname.edit', $so->id) }}">
                                <x-ui.button size="sm" variant="secondary">Edit</x-ui.button>
                            </a>
													@else
													 <a href="{{ route('admin.stok-opname.show', $so->id) }}">
                                <x-ui.button size="sm" variant="secondary">Detail</x-ui.button>
                            </a>
													@endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            @if(request('search') || request('status'))
                                Tidak ada data stok opname yang cocok dengan pencarian.
                            @else
                                Belum ada data stok opname.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $stokOpnames->appends(request()->query())->links() }}</div>
</div>
@endsection
