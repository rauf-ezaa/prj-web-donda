@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Pengembalian'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
            Verifikasi Pengembalian
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Daftar pengembalian yang menunggu verifikasi tahap pertama (Admin).
        </p>
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
                    <th class="px-4 py-3">Kode Peminjaman</th>
                    <th class="px-4 py-3">Nama Peminjam</th>
                    <th class="px-4 py-3">Jabatan</th>
                    <th class="px-4 py-3">Tanggal Diajukan</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($pengembalians as $index => $p)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $pengembalians->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $p->peminjaman->kode_peminjaman }}</td>
                        <td class="px-4 py-3">{{ $p->peminjaman->requestedBy->nama_karyawan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $p->staff->nama_karyawans }}</td>
                        <td class="px-4 py-3">{{ $p->tanggal_pengembalian->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.pengembalian.show', $p->id) }}">
                                <x-ui.button size="sm" variant="primary">Verifikasi</x-ui.button>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            Tidak ada pengembalian yang perlu diverifikasi saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pengembalians->links() }}
    </div>
</div>
@endsection
