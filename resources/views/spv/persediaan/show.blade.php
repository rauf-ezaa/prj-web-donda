@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Persediaan'; @endphp
@section('content')

<div x-data="approvalPersediaan({ persedianId: {{ $persedian->id }} })" class="max-w-2xl mx-auto">
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Verifikasi Barang Masuk</h3>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        {{ $persedian->barang->nama_barang ?? '-' }}
    </p>

    <div x-show="errorMessage" x-cloak class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

    <x-common.component-card title="Detail Barang Masuk">
        <div class="grid grid-cols-2 gap-4 mb-4">
					 <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Nama Barang</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $persedian->barang->nama_barang ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Qty</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $persedian->qty }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Asal Dana</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ strtoupper($persedian->asal_dana) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Harga Satuan</p>
                <p class="text-sm text-gray-800 dark:text-white/90">Rp{{ number_format($persedian->harga_satuan_unit, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Harga</p>
                <p class="text-sm text-gray-800 dark:text-white/90">Rp{{ number_format($persedian->harga_total, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Masuk</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $persedian->tanggal_masuk }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Stok Saat Ini</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $persedian->barang->stok_tersedia ?? 0 }}</p>
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Catatan (opsional untuk setuju, wajib untuk tolak)
            </label>
            <textarea
                x-model="catatan"
                rows="2"
                placeholder="Catatan approval"
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
            ></textarea>
        </div>

        <div class="flex gap-2">
            <x-ui.button size="sm" variant="primary" type="button" @click="approve" x-bind:disabled="isProcessing">
                <span x-show="!isProcessing">Setujui</span>
                <span x-show="isProcessing" x-cloak>Memproses...</span>
            </x-ui.button>
            <x-ui.button size="sm" variant="secondary" type="button" @click="reject" x-bind:disabled="isProcessing">
                Tolak
            </x-ui.button>
        </div>
    </x-common.component-card>
</div>

@verbatim
<script>
function approvalPersediaan({ persedianId }) {
    return {
        persedianId,
        catatan: '',
        isProcessing: false,
        errorMessage: '',

        async approve() {
            this.errorMessage = '';
            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/persediaan/${this.persedianId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ catatan_approval: this.catatan }),
                });

                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }

                const data = await res.json();
                this.errorMessage = data.message || 'Gagal menyetujui persediaan';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isProcessing = false;
            }
        },

        async reject() {
            this.errorMessage = '';

            if (!this.catatan.trim()) {
                this.errorMessage = 'Catatan wajib diisi untuk menolak';
                return;
            }

            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/persediaan/${this.persedianId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ catatan_approval: this.catatan }),
                });

                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }

                const data = await res.json();
                this.errorMessage = data.message || 'Gagal menolak persediaan';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isProcessing = false;
            }
        },
    };
}
</script>
@endverbatim

@endsection
