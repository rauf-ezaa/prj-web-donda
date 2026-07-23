{{-- resources/views/components/pengembalian/detail-verifikasi.blade.php --}}
@props(['pengembalian', 'role', 'verifyRoute', 'rejectRoute'])

<div class="p-4 md:p-6 max-w-3xl mx-auto">

    @if($role === 'supervisor')
        <div class="mb-4 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
            <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-success-600 dark:bg-success-500/15 dark:text-success-400">
                ✓ Diverifikasi Admin
            </span>
            <span>oleh {{ $pengembalian->adminVerifikator->name }} · {{ $pengembalian->diverifikasi_admin_at->format('d M Y, H:i') }}</span>
        </div>
    @endif

    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-1">
        {{ $role === 'admin' ? 'Verifikasi Pengembalian' : 'Verifikasi Final' }} — {{ $pengembalian->peminjaman->kode_peminjaman }}
    </h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Diajukan oleh {{ $pengembalian->staff->name }} · {{ $pengembalian->tanggal_pengembalian->format('d M Y, H:i') }}
    </p>

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3">Baik</th>
                    <th class="px-4 py-3">Rusak</th>
                    <th class="px-4 py-3">Hilang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($pengembalian->details as $index => $d)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">{{ $d->peminjamanDetail->barang->nama_barang }}</td>
                        <td class="px-4 py-3">{{ $d->qty_baik }}</td>
                        <td class="px-4 py-3">{{ $d->qty_rusak }}</td>
                        <td class="px-4 py-3">{{ $d->qty_hilang }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pengembalian->catatan)
        <div class="mb-6 rounded-lg bg-gray-50 p-3 text-sm text-gray-600 dark:bg-white/[0.02] dark:text-gray-400">
            <strong>Catatan:</strong> {{ $pengembalian->catatan }}
        </div>
    @endif

    @if($role === 'supervisor')
        <div class="mb-6 rounded-lg bg-warning-50 p-3 text-xs text-warning-600 dark:bg-warning-500/10 dark:text-warning-400">
            Setelah verifikasi ini, stok barang akan langsung diperbarui (baik & rusak masuk stok, hilang tidak).
        </div>
    @endif

    <div class="flex gap-2">
        <form action="{{ $verifyRoute }}" method="POST">
            @csrf
            <x-ui.button size="md" variant="primary" type="submit">
                {{ $role === 'admin' ? 'Teruskan ke Supervisor' : 'Verifikasi Final & Update Stok' }}
            </x-ui.button>
        </form>

        <form action="{{ $rejectRoute }}" method="POST" onsubmit="return confirm('Yakin tolak pengembalian ini?')">
            @csrf
            <input type="hidden" name="alasan" value="Ditolak oleh {{ $role }}">
            <x-ui.button size="md" variant="secondary" type="submit">Tolak</x-ui.button>
        </form>
    </div>
</div>
