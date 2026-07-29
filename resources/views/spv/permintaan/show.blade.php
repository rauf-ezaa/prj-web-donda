@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Pengajuan'; @endphp
@section('content')

 						@php
								$opnameLockService = app(\App\Services\OpnameLockService::class);
								$activeLock = $opnameLockService->activeLock();
						@endphp

<div x-data="approvalPermintaan({
        permintaanId: {{ $permintaan->id }},
        items: @js($permintaan->details->map(fn($d) => [
            'detail_id' => $d->id,
            'nama_barang' => $d->barang->nama_barang,
            'jumlah_diminta' => $d->jumlah_diminta,
            'stok_tersedia' => $d->barang->stok_tersedia,
            'jumlah_disetujui' => $d->jumlah_diminta,
        ])),
    })"
    class="max-w-2xl mx-auto"
>


<div class="flex justify-between items-baseline mb-1">
	<h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Verifikasi Permintaan</h3>
	<span class="text-xs text-gray-400 font-mono">{{ $permintaan->kode_permintaan }}</span>
</div>
<p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
	Diajukan oleh {{ $permintaan->requestedBy->nama_karyawan ?? '-' }} · {{ $permintaan->keperluan }}
</p>

<div x-show="errorMessage" x-cloak class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>


<x-common.component-card title="Daftar Barang Diminta">
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-5">
            <template x-for="(item, index) in items" :key="item.detail_id">
                <div class="px-4 py-3" :class="{ 'border-b border-gray-200 dark:border-gray-700': index < items.length - 1 }">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm text-gray-800 dark:text-white/90 m-0" x-text="item.nama_barang"></p>
                            <p class="text-xs text-gray-400 m-0">
                                Diminta: <span x-text="item.jumlah_diminta"></span> ·
                                Stok tersedia: <span x-text="item.stok_tersedia"></span>
                            </p>
                        </div>
                        <span
                            x-show="item.jumlah_disetujui > item.stok_tersedia"
                            x-cloak
                            class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-error-500 whitespace-nowrap"
                        >Stok kurang</span>
                    </div>

                </div>
            </template>
        </div>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Catatan (opsional untuk approve, wajib untuk tolak)
            </label>
            <textarea
                x-model="catatan"
                rows="2"
                placeholder="Catatan approval"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
            ></textarea>
        </div>
				@if($activeLock)
					<div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
							⚠ Sistem sedang dalam proses Stok Opname ({{ $activeLock->no_bast }}), transaksi yang mempengaruhi stok dibekukan sementara.
					</div>
				@else
        <div class="flex gap-2">
            <x-ui.button size="sm" variant="primary" type="button" @click="approve" x-bind:disabled="isProcessing">
                <span x-show="!isProcessing">Setujui</span>
                <span x-show="isProcessing" x-cloak>Memproses...</span>
            </x-ui.button>
            <x-ui.button size="sm" variant="secondary" type="button" @click="reject" x-bind:disabled="isProcessing">
                Tolak
            </x-ui.button>
        </div>
				@endif
    </x-common.component-card>
</div>

<script>
function approvalPermintaan({ permintaanId, items }) {
    return {
        permintaanId,
        items,
        catatan: '',
        isProcessing: false,
        errorMessage: '',

        async approve() {
            this.errorMessage = '';

            const overStock = this.items.some(i => i.jumlah_disetujui > i.stok_tersedia);
            if (overStock) {
                this.errorMessage = 'Ada jumlah disetujui yang melebihi stok tersedia';
                return;
            }

            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/permintaan/${this.permintaanId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        items: this.items.map(i => ({
                            detail_id: i.detail_id,
                            jumlah_disetujui: i.jumlah_disetujui,
                        })),
                        catatan_approval: this.catatan,
                    }),
                });

                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }

                const data = await res.json();
                this.errorMessage = data.message || 'Gagal menyetujui permintaan';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isProcessing = false;
            }
        },

        async reject() {
            this.errorMessage = '';

            if (!this.catatan.trim()) {
                this.errorMessage = 'Catatan wajib diisi untuk menolak permintaan';
                return;
            }

            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/permintaan/${this.permintaanId}/reject`, {
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
                this.errorMessage = data.message || 'Gagal menolak permintaan';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isProcessing = false;
            }
        },
    };
}
</script>
@endsection
