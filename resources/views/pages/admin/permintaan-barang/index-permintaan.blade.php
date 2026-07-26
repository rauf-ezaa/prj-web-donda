@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6">

    <!-- Header -->
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
                Permintaan Barang
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pilih status untuk menampilkan daftar permintaan.
            </p>
        </div>

								@if($draftAktif)
						<div class="flex flex-col items-end gap-1">
								<a href="{{ route('permintaan.draft', $draftAktif->id) }}">
										<x-ui.button size="md" variant="primary">
												Lanjutkan Draft ({{ $draftAktif->kode_permintaan }})
										</x-ui.button>
								</a>
								<p class="text-xs text-gray-400">
										Selesaikan draft yang sedang berjalan sebelum membuat transaksi baru.
								</p>
						</div>
				@else
				<form action="{{ route('permintaan.draft.start') }}" method="POST">
								@csrf
								<x-ui.button size="md" variant="primary" type="submit">
										+ Buat Transaksi Baru
								</x-ui.button>
						</form>
				@endif

    </div>

    <!-- Sort / Filter -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <form method="GET" action="{{ route('permintaan.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="min-w-[220px]">
                <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">
                    Status Persetujuan
                </label>
                <select name="sort"
                    class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">-- Pilih Status --</option>
                    <option value="draft" @selected(request('sort') == 'draft')>Draft (Belum Diajukan)</option>
                    <option value="pending" @selected(request('sort') == 'pending')>Menunggu Persetujuan</option>
                    <option value="approved" @selected(request('sort') == 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('sort') == 'rejected')>Ditolak</option>
                </select>
            </div>

            <x-ui.button size="md" variant="primary" type="submit">Tampilkan</x-ui.button>

            @if(request('sort'))
                <a href="{{ route('permintaan.index') }}">
                    <x-ui.button size="md" variant="secondary" type="button">Reset</x-ui.button>
                </a>
            @endif
        </form>
    </div>

    <!-- Table / Empty State -->
    @if(!request('sort'))
        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 py-16 text-center dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Silakan pilih status persetujuan terlebih dahulu
            </p>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                Data akan muncul setelah kamu memilih salah satu status di atas.
            </p>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-white/[0.02]">
                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Peminjam</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($permintaan as $item)
                        <tr class="text-gray-700 dark:text-gray-300">
                            <td class="px-4 py-3 font-medium">{{ $item->kode_permintaan }}</td>
                            <td class="px-4 py-3">{{ $item->requestedBy->nama_karyawan }}</td>
                            <td class="px-4 py-3">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :status="$item->status_permintaan" />
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if($item->status_permintaan === 'draft')
                                    <a href="{{ route('permintaan.draft', $item->id) }}">
                                        <x-ui.button size="sm" variant="primary">Lanjutkan Draft</x-ui.button>
                                    </a>
                                @else
                                    <a href="{{ route('permintaan.show', $item->id) }}">
                                        <x-ui.button size="sm" variant="secondary">Lihat Detail</x-ui.button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                Tidak ada data untuk status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $permintaan->links() }}
        </div>
    @endif

</div>
@endsection
