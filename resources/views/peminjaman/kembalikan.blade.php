@extends('layouts.app')
@php $currentPageTitle = 'Pengembalian Barang'; @endphp
@section('content')

<div x-data="prosesKembalikan({
        peminjamanId: {{ $peminjaman->id }},
        items: @js($peminjaman->details->map(fn($d) => [
            'detail_id' => $d->id,
            'nama_barang' => $d->barang->nama_barang,
            'jumlah_pinjam' => $d->jumlah_disetujui ?? $d->jumlah_pinjam,
            'kondisi_kembali' => 'baik',
            'catatan_kembali' => '',
        ])),
    })"
    class="max-w-2xl mx-auto"
>
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Pengembalian Barang</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $peminjaman->kode_peminjaman }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        Isi kondisi tiap barang saat dikembalikan. SPV akan konfirmasi setelah barang diterima secara fisik.
    </p>

    <div x-show="errorMessage" x-cloak class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

    <x-common.component-card title="Kondisi Barang">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-5">
            <template x-for="(item, index) in items" :key="item.detail_id">
                <div class="px-4 py-3" :class="{ 'border-b border-gray-200 dark:border-gray-700': index < items.length - 1 }">
                    <div class="mb-2">
                        <p class="text-sm text-gray-800 dark:text-white/90 m-0" x-text="item.nama_barang"></p>
                        <p class="text-xs text-gray-400 m-0" x-text="`Jumlah dipinjam: ${item.jumlah_pinjam}`"></p>
                    </div>

                    <div class="mb-2">
                        <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Kondisi saat kembali</label>
                        <select
                            x-model="item.kondisi_kembali"
                            class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        >
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak ringan</option>
                            <option value="rusak_berat">Rusak berat</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>

                    <div x-show="item.kondisi_kembali !== 'baik'" x-cloak>
                        <label class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Catatan (jelaskan kondisi)</label>
                        <textarea
                            x-model="item.catatan_kembali"
                            rows="2"
                            placeholder="Contoh: layar retak akibat terjatuh"
                            class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                        ></textarea>
                    </div>
                </div>
            </template>
        </div>

        <x-ui.button size="sm" variant="primary" type="button" @click="submitKembalikan" x-bind:disabled="isSubmitting">
            <span x-show="!isSubmitting">Ajukan Pengembalian</span>
            <span x-show="isSubmitting" x-cloak>Memproses...</span>
        </x-ui.button>
    </x-common.component-card>
</div>

@verbatim
<script>
function prosesKembalikan({ peminjamanId, items }) {
    return {
        peminjamanId,
        items,
        isSubmitting: false,
        errorMessage: '',

        async submitKembalikan() {
            this.errorMessage = '';

            const belumLengkap = this.items.some(i =>
                i.kondisi_kembali !== 'baik' && !i.catatan_kembali.trim()
            );

            if (belumLengkap) {
                this.errorMessage = 'Isi catatan untuk barang yang kondisinya tidak baik';
                return;
            }

            this.isSubmitting = true;

            try {
                const res = await fetch(`/peminjaman/${this.peminjamanId}/kembalikan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        items: this.items.map(i => ({
                            detail_id: i.detail_id,
                            kondisi_kembali: i.kondisi_kembali,
                            catatan_kembali: i.catatan_kembali,
                        })),
                    }),
                });

                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }

                const data = await res.json();
                this.errorMessage = data.message || 'Gagal mengajukan pengembalian';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isSubmitting = false;
            }
        },
    };
}
</script>
@endverbatim

@endsection
