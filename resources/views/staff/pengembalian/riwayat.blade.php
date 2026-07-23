@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
                Riwayat Pengembalian
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Status pengajuan pengembalian yang pernah kamu buat.
            </p>
        </div>
        <a href="{{ route('pengembalian.index') }}">
            <x-ui.button size="md" variant="secondary">← Kembali</x-ui.button>
        </a>
    </div>

    <div class="mb-5">
        <form method="GET" action="{{ route('pengembalian.riwayat') }}" class="flex gap-2">
            <select name="status" onchange="this.form.submit()"
                class="h-10 rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi_admin" @selected(request('status') == 'menunggu_verifikasi_admin')>Menunggu Verifikasi Admin</option>
                <option value="menunggu_verifikasi_spv" @selected(request('status') == 'menunggu_verifikasi_spv')>Menunggu Verifikasi Supervisor</option>
                <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                <option value="ditolak_admin" @selected(request('status') == 'ditolak_admin')>Ditolak Admin</option>
                <option value="ditolak_spv" @selected(request('status') == 'ditolak_spv')>Ditolak Supervisor</option>
            </select>
        </form>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">Kode Peminjaman</th>
                    <th class="px-4 py-3">Tanggal Diajukan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($pengembalians as $p)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3 font-medium">{{ $p->peminjaman->kode_peminjaman }}</td>
                        <td class="px-4 py-3">{{ $p->tanggal_pengembalian->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs">{{ $p->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <!-- <a href="{{ route('pengembalian.riwayat.show', $p->id) }}">
                                <x-ui.button size="sm" variant="secondary">Detail</x-ui.button>
                            </a> -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                            Belum ada riwayat pengembalian.
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
