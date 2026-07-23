@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Pengajuan'; @endphp
@section('content')

<div x-data="approvalPengajuan({ pengajuanId: {{ $pengajuan->id }} })" class="max-w-2xl mx-auto">
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Verifikasi Pengajuan </h3>
        <span class="text-xs text-gray-400 font-mono">{{ $pengajuan->kode_pengajuan }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        Diajukan oleh {{ $pengajuan->requestedBy->nama_karyawan ?? '-' }}
    </p>

    <div x-show="errorMessage" x-cloak class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

    <x-common.component-card title="Detail Pengajuan">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Alasan Pengajuan</p>
        <p class="text-sm text-gray-800 dark:text-white/90 mb-4">{{ $pengajuan->alasan_pengajuan }}</p>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-2">
            @foreach ($pengajuan->details as $detail)
                <div class="flex items-center justify-between px-3.5 py-2.5 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                    <div>
											<p class="text-sm m-0 text-gray-800 dark:text-white/90">{{ $detail->nama_barang_diajukan }}</p>
                        <p class="text-xs text-gray-400 m-0">
                            {{ $detail->jumlah_diajukan }} x Rp{{ number_format($detail->estimasi_harga_satuan, 0, ',', '.') }}
                        </p>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                    Total yang diajukan : {{$detail->jumlah_diajukan}}
									</span>
                </div>
            @endforeach
        </div>

        <p class="text-sm text-right text-gray-800 dark:text-white/90 mb-5 font-medium">
            Total yang diajukan : {{$detail->jumlah_diajukan}}
        </p>

        <div class="mb-5">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Catatan (opsional untuk approve, wajib untuk tolak)
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
function approvalPengajuan({ pengajuanId }) {
    return {
        pengajuanId,
        catatan: '',
        isProcessing: false,
        errorMessage: '',

        async approve() {
            this.errorMessage = '';
            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/pengajuan/${this.pengajuanId}/approve`, {
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
                this.errorMessage = data.message || 'Gagal menyetujui pengajuan';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isProcessing = false;
            }
        },

        async reject() {
            this.errorMessage = '';

            if (!this.catatan.trim()) {
                this.errorMessage = 'Catatan wajib diisi untuk menolak pengajuan';
                return;
            }

            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/pengajuan/${this.pengajuanId}/reject`, {
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
                this.errorMessage = data.message || 'Gagal menolak pengajuan';

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
