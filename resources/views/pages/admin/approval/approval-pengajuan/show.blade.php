@extends('layouts.app')
@php $currentPageTitle = 'Detail Pengajuan'; @endphp
@section('content')

<div x-data="approvalPengajuan({ pengajuanId: {{ $pengajuan->id }} })" class="max-w-2xl mx-auto">
    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Detail Pengajuan</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $pengajuan->kode_pengajuan }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        Diajukan oleh {{ $pengajuan->requestedBy->nama_karyawan ?? '-' }}
    </p>

    {{-- STATUS BANNER --}}
    @php
        $bannerConfig = match($pengajuan->status) {
            'approved' => ['bg' => 'bg-green-50 dark:bg-green-950/30', 'text' => 'text-green-700 dark:text-green-400', 'icon' => 'ti-circle-check', 'label' => 'Disetujui SPV'],
            'rejected' => ['bg' => 'bg-red-50 dark:bg-red-950/30', 'text' => 'text-error-500', 'icon' => 'ti-circle-x', 'label' => 'Ditolak'],
            'menunggu_spv' => ['bg' => 'bg-amber-50 dark:bg-amber-950/30', 'text' => 'text-amber-600', 'icon' => 'ti-clock', 'label' => 'Menunggu Persetujuan SPV'],
            'pending' => ['bg' => 'bg-blue-50 dark:bg-blue-950/30', 'text' => 'text-blue-700 dark:text-blue-400', 'icon' => 'ti-hourglass', 'label' => 'Menunggu Verifikasi Admin'],
            default => ['bg' => 'bg-gray-50 dark:bg-gray-800', 'text' => 'text-gray-600 dark:text-gray-400', 'icon' => 'ti-info-circle', 'label' => ucfirst($pengajuan->status)],
        };
    @endphp

    <div class="{{ $bannerConfig['bg'] }} rounded-xl p-4 mb-5">
        <div class="flex items-start gap-2.5">
            <i class="ti {{ $bannerConfig['icon'] }} text-lg {{ $bannerConfig['text'] }} mt-0.5"></i>
            <div>
                <p class="text-sm font-medium {{ $bannerConfig['text'] }} m-0">{{ $bannerConfig['label'] }}</p>
                <p class="text-xs {{ $bannerConfig['text'] }} mt-1 m-0">
                    @if ($pengajuan->status === 'approved')
                        Disetujui oleh {{ $pengajuan->approvedBy->nama_karyawan ?? '-' }} pada {{ $pengajuan->approved_at?->translatedFormat('d F Y, H:i') }}.
                        @if ($pengajuan->catatan_approval)
                            Catatan: {{ $pengajuan->catatan_approval }}
                        @endif
                    @elseif ($pengajuan->status === 'rejected')
                        Ditolak oleh {{ $pengajuan->approvedBy->nama_karyawan ?? '-' }} pada {{ $pengajuan->approved_at?->translatedFormat('d F Y, H:i') }}.
                        @if ($pengajuan->catatan_approval)
                            Alasan: {{ $pengajuan->catatan_approval }}
                        @endif
                    @elseif ($pengajuan->status === 'menunggu_spv')
                        Diteruskan oleh admin {{ $pengajuan->verifiedByAdmin->nama_karyawan ?? '-' }}, menunggu keputusan Anda.
                    @elseif ($pengajuan->status === 'pending')
                        Belum diverifikasi admin.
                    @endif
                </p>
            </div>
        </div>
    </div>

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
                           Quantity / Jumlah Diajukan :  {{ $detail->jumlah_diajukan }}
                        </p>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">

                    </span>
                </div>
            @endforeach
        </div>

        {{-- FORM APPROVAL — cuma tampil kalau masih actionable --}}
        @if ($pengajuan->is_actionable_admin)
            <div class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Catatan (opsional untuk setuju, wajib untuk tolak)
                </label>
                <textarea x-model="catatan" rows="2" placeholder="Catatan approval"
                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
            </div>

            <div class="flex gap-2">
                <x-ui.button size="sm" variant="primary" type="button" @click="approve" x-bind:disabled="isProcessing">
                    <span x-show="!isProcessing">Setujui</span>
                    <span x-show="isProcessing" x-cloak>Memproses...</span>
                </x-ui.button>
                <x-ui.button size="sm" variant="secondary" type="button" @click="reject" x-bind:disabled="isProcessing">
                    Tolak
                </x-ui.button>
								<a href="{{ url()->previous() }}">
                <x-ui.button size="sm" variant="secondary" type="button" class="w-full">
                    <i class="ti ti-arrow-left"></i> Kembali
                </x-ui.button>
            </a>
            </div>
        @else
            <a href="{{ route('admin.pengajuan.index') }}">
                <x-ui.button size="sm" variant="secondary" type="button" class="w-full">
                    <i class="ti ti-arrow-left"></i> Kembali
                </x-ui.button>
            </a>
        @endif
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
                const res = await fetch(`/admin/pengajuan/${this.pengajuanId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ catatan_approval: this.catatan }),
                });

                if (res.redirected) { window.location.href = res.url; return; }

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
                const res = await fetch(`/admin/pengajuan/${this.pengajuanId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ catatan_admin: this.catatan }),
                });

                if (res.redirected) { window.location.href = res.url; return; }

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
