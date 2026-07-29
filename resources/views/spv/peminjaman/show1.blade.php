@extends('layouts.app')
@php $currentPageTitle = 'Verifikasi SPV - Peminjaman'; @endphp
@section('content')


<div x-data="verifikasiPeminjaman({
	peminjamanId: {{ $peminjaman->id }},
	items: @js($peminjaman->details->map(fn($d) => [
	'detail_id' => $d->id,
	'nama_barang' => $d->barang->nama_barang,
	'jumlah_pinjam' => $d->qty_pinjam,
	'stok_tersedia' => $d->barang->stok_tersedia,
	])),
	})"
	class="max-w-2xl mx-auto"
	>


	@php
		 $opnameLockService = app(\App\Services\OpnameLockService::class);
		 $activeLock = $opnameLockService->activeLock();

        $bannerConfig = match($peminjaman->status) {
            'dipinjam' => ['bg' => 'bg-green-50 dark:bg-green-950/30', 'text' => 'text-green-700 dark:text-green-400', 'icon' => 'ti-circle-check', 'label' => 'Disetujui SPV'],
            'ditolak' => ['bg' => 'bg-red-50 dark:bg-red-950/30', 'text' => 'text-error-500', 'icon' => 'ti-circle-x', 'label' => 'Ditolak SPV'],
            'menunggu_spv' => ['bg' => 'bg-amber-50 dark:bg-amber-950/30', 'text' => 'text-amber-600', 'icon' => 'ti-clock', 'label' => 'Menunggu Persetujuan SPV'],
            default => ['bg' => 'bg-gray-50 dark:bg-gray-800', 'text' => 'text-gray-600 dark:text-gray-400', 'icon' => 'ti-info-circle', 'label' => ucfirst($peminjaman->status)],
        };
    @endphp


    <div class="flex justify-between items-baseline mb-1">
        <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">Verifikasi SPV</h3>
        <span class="text-xs text-gray-400 font-mono">{{ $peminjaman->kode_peminjaman }}</span>
    </div>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        {{ $peminjaman->requestedBy->nama_karyawan ?? '-' }} · {{ $peminjaman->keperluan }}
    </p>

    <div class="{{ $bannerConfig['bg'] }} rounded-xl p-4 mb-5">
        <div class="flex items-start gap-2.5">
            <i class="ti {{ $bannerConfig['icon'] }} text-lg {{ $bannerConfig['text'] }} mt-0.5"></i>
            <div>
                <p class="text-sm font-medium {{ $bannerConfig['text'] }} m-0">{{ $bannerConfig['label'] }}</p>
                <p class="text-xs {{ $bannerConfig['text'] }} mt-1 m-0">
                    @if ($peminjaman->status === 'dipinjam')
                        Disetujui oleh {{ $peminjaman->approvedBy->nama_karyawan ?? '-' }} pada {{ $peminjaman->approved_at?->translatedFormat('d F Y, H:i') }}. Stok telah diperbarui.
                    @elseif ($peminjaman->status === 'ditolak')
                        Ditolak oleh {{ $peminjaman->approvedBy->nama_karyawan ?? '-' }} pada {{ $peminjaman->approved_at?->translatedFormat('d F Y, H:i') }}.
                        @if ($peminjaman->catatan_approval)
                            Alasan: {{ $peminjaman->catatan_approval }}
                        @endif
                    @elseif ($peminjaman->status === 'menunggu_spv')
                        Menunggu keputusan Anda.
                    @else
                        Status saat ini: {{ str_replace('_', ' ', $peminjaman->status) }}
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div x-show="errorMessage" x-cloak class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

    <x-common.component-card title="Daftar Barang Yang Dipinjam">

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tanggal Pinjam</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $peminjaman->tanggal_pinjam->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Wajib Kembali</p>
                <p class="text-sm text-gray-800 dark:text-white/90">{{ $peminjaman->tanggal_wajib_kembali->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-5">
            <template x-for="(item, index) in items" :key="item.detail_id">
                <div class="px-3.5 py-2.5" :class="{ 'border-b border-gray-200 dark:border-gray-700': index < items.length - 1 }">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-800 dark:text-white/90 m-0" x-text="item.nama_barang"></p>
                            <p class="text-xs text-gray-400 m-0">
                                Jumlah: <span x-text="item.jumlah_pinjam"></span> ·
                                Stok tersedia: <span x-text="item.stok_tersedia"></span>
                            </p>
                        </div>
                        <span x-show="item.jumlah_pinjam > item.stok_tersedia" x-cloak
                            class="text-xs px-2 py-0.5 rounded-full bg-red-50 text-error-500 whitespace-nowrap">
                            Stok kurang
                        </span>
                    </div>
                </div>
            </template>
        </div>

								@if($activeLock)
								<div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
										⚠ Sistem sedang dalam proses Stok Opname ({{ $activeLock->no_bast }}), transaksi yang mempengaruhi stok dibekukan sementara.
									</div>
									<div class="mb-5">
											<a href="{{ route('spv.peminjaman.index') }}">
												<x-ui.button size="sm" variant="danger" type="button">
													<i class="ti ti-arrow-left"></i> Kembali
												</x-ui.button>
											</a>
							@else
						 @if ($peminjaman->status === 'menunggu_spv')
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
                <a href="{{ route('spv.peminjaman.index') }}">
                    <x-ui.button size="sm" variant="danger" type="button">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </x-ui.button>
                </a>
            </div>
        @else
				<a href="{{ route('spv.peminjaman.index') }}">
						<x-ui.button size="sm" variant="danger" type="button" class="w-full">
								<i class="ti ti-arrow-left"></i> Kembali
						</x-ui.button>
				</a>
				@endif

        @endif
    </x-common.component-card>
</div>

@verbatim
<script>
function verifikasiPeminjaman({ peminjamanId, items }) {
    return {
        peminjamanId,
        items,
        catatan: '',
        isProcessing: false,
        errorMessage: '',

        async approve() {
            this.errorMessage = '';

            const overStock = this.items.some(i => i.jumlah_pinjam > i.stok_tersedia);
            if (overStock) {
                this.errorMessage = 'Ada barang yang jumlah pinjamnya melebihi stok tersedia';
                return;
            }

            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/peminjaman/${this.peminjamanId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        catatan_approval: this.catatan,
                    }),
                });

                if (res.redirected) { window.location.href = res.url; return; }

                const data = await res.json();
                this.errorMessage = data.message || 'Gagal menyetujui peminjaman';

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isProcessing = false;
            }
        },

        async reject() {
            this.errorMessage = '';

            if (!this.catatan.trim()) {
                this.errorMessage = 'Catatan wajib diisi untuk menolak peminjaman';
                return;
            }

            this.isProcessing = true;

            try {
                const res = await fetch(`/spv/peminjaman/${this.peminjamanId}/reject`, {
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
                this.errorMessage = data.message || 'Gagal menolak peminjaman';

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
