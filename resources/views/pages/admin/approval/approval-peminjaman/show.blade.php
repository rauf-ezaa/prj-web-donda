@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi Admin - peminjaman'; @endphp
@section('content')

<div x-data="approvalPermintaan({
        permintaanId: {{ $peminjaman->id }},
        items: @js($peminjaman->details->map(fn($d) => [
            'detail_id' => $d->id,
            'nama_barang' => $d->barang->nama_barang,
            'jumlah_diminta' => $d->jumlah_diminta,
            'stok_tersedia' => $d->barang->stok_tersedia,
            'jumlah_disetujui' => $d->jumlah_disetujui ?? $d->jumlah_diminta,
        ])),
    })"
    class="max-w-2xl mx-auto"
>

    @php
        $bannerConfig = match($peminjaman->status) {
            'approved' => ['bg' => 'bg-green-50 dark:bg-green-950/30', 'text' => 'text-green-700 dark:text-green-400', 'icon' => 'ti-circle-check', 'label' => 'Disetujui SPV'],
            'rejected' => ['bg' => 'bg-red-50 dark:bg-red-950/30', 'text' => 'text-error-500', 'icon' => 'ti-circle-x', 'label' => 'Ditolak SPV'],
            'menunggu_spv' => ['bg' => 'bg-amber-50 dark:bg-amber-950/30', 'text' => 'text-amber-600', 'icon' => 'ti-clock', 'label' => 'Menunggu Persetujuan SPV'],
            default => ['bg' => 'bg-gray-50 dark:bg-gray-800', 'text' => 'text-gray-600 dark:text-gray-400', 'icon' => 'ti-info-circle', 'label' => ucfirst($peminjaman->status)],
        };
    @endphp

		 <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Verifikasi Admin</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $peminjaman->kode_peminjaman }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        {{ $peminjaman->requestedBy->nama_karyawan ?? '-' }} · Jabatan  · {{ $peminjaman->keperluan }}
				<br>
    </p>

		<div class="{{ $bannerConfig['bg'] }} rounded-xl p-4 mb-5">
        <div class="flex items-start gap-2.5">
            <i class="ti {{ $bannerConfig['icon'] }} text-lg {{ $bannerConfig['text'] }} mt-0.5"></i>
            <div>
                <p class="text-sm font-medium {{ $bannerConfig['text'] }} m-0">{{ $bannerConfig['label'] }}</p>
                <p class="text-xs {{ $bannerConfig['text'] }} mt-1 m-0">
                    @if ($peminjaman->status === 'approved')
                        Disetujui oleh {{ $peminjaman->approvedBy->nama_karyawan ?? '-' }} pada {{ $peminjaman->approved_at?->translatedFormat('d F Y, H:i') }}. Stok telah diperbarui.
                    @elseif ($peminjaman->status === 'rejected')
                        Ditolak oleh {{ $peminjaman->approvedBy->nama_karyawan ?? '-' }} pada {{ $peminjaman->approved_at?->translatedFormat('d F Y, H:i') }}.
                        @if ($peminjaman->catatan_approval)
                            Alasan: {{ $peminjaman->catatan_approval }}
                        @endif
                    @elseif ($peminjaman->status === 'menunggu_spv')
                        Diteruskan oleh admin {{ $peminjaman->verifiedByAdmin->nama_karyawan ?? '-' }}, menunggu keputusan Anda.
                    @else
                        Status saat ini: {{ str_replace('_', ' ', $peminjaman->status) }}
                    @endif
                </p>
            </div>
        </div>
    </div>

<div x-data="verifikasiAdmin({ id: {{ $peminjaman->id }} })" class="max-w-2xl mx-auto">



    <div x-show="errorMessage" x-cloak class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

    <x-common.component-card title="Daftar Barang Yang Dipinjam">
    {{-- detail barang, SELALU tampil apapun statusnya --}}
    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-5">
        @foreach ($peminjaman->details as $detail)
            <div class="flex items-center justify-between px-3.5 py-2.5 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                <p class="text-sm m-0 text-gray-800 dark:text-white/90">{{ $detail->barang->nama_barang }}</p>
                <span class="text-xs text-gray-400">
                    Diminta: {{ $detail->qty_pinjam }}
                    @if ($detail->jumlah_disetujui !== null)
                        · Disetujui: {{ $detail->jumlah_disetujui }}
                    @endif
                </span>
            </div>
        @endforeach
    </div>

    @if ($peminjaman->is_actionable)
            <div class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Catatan (opsional untuk approve, wajib untuk tolak)
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
								<a href="{{ route('admin.peminjaman.index') }}">
										<x-ui.button size="sm" variant="danger" type="button" class="w-full">
												<i class="ti ti-arrow-left"></i> Kembali
										</x-ui.button>
								</a>
            </div>
        @else
				<a href="{{ route('admin.peminjaman.index') }}">
										<x-ui.button size="sm" variant="danger" type="button" class="w-full">
												<i class="ti ti-arrow-left"></i> Kembali
										</x-ui.button>
								</a>
        @endif
</x-common.component-card>
</div>

<script>
function verifikasiAdmin({ id }) {
    return {
        id,
        catatan: '',
        isProcessing: false,
        errorMessage: '',

        async approve() {
            this.errorMessage = '';
            this.isProcessing = true;
            try {
                const res = await fetch(`/admin/peminjaman/${this.id}/approve`, {
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
                this.errorMessage = data.message || 'Gagal memproses';
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
                const res = await fetch(`/admin/peminjaman/${this.id}/reject`, {
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
                this.errorMessage = data.message || 'Gagal memproses';
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
