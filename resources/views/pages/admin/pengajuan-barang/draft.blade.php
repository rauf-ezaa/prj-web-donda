@extends('layouts.app')
@php
    use Illuminate\Support\HtmlString;

    // Page title
    $currentPageTitle = 'Buttons';

    // Define BoxIcon once as an HtmlString (so it won’t get escaped)
    $BoxIcon = new HtmlString('
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M9.77692 3.24224C9.91768 3.17186 10.0834 3.17186 10.2241 3.24224L15.3713 5.81573L10.3359 8.33331C10.1248 8.43888 9.87626 8.43888 9.66512 8.33331L4.6298 5.81573L9.77692 3.24224ZM3.70264 7.0292V13.4124C3.70264 13.6018 3.80964 13.775 3.97903 13.8597L9.25016 16.4952L9.25016 9.7837C9.16327 9.75296 9.07782 9.71671 8.99432 9.67496L3.70264 7.0292ZM10.7502 16.4955V9.78396C10.8373 9.75316 10.923 9.71683 11.0067 9.67496L16.2984 7.0292V13.4124C16.2984 13.6018 16.1914 13.775 16.022 13.8597L10.7502 16.4955ZM9.41463 17.4831L9.10612 18.1002C9.66916 18.3817 10.3319 18.3817 10.8949 18.1002L16.6928 15.2013C17.3704 14.8625 17.7984 14.17 17.7984 13.4124V6.58831C17.7984 5.83076 17.3704 5.13823 16.6928 4.79945L10.8949 1.90059C10.3319 1.61908 9.66916 1.61907 9.10612 1.90059L9.44152 2.57141L9.10612 1.90059L3.30823 4.79945C2.63065 5.13823 2.20264 5.83076 2.20264 6.58831V13.4124C2.20264 14.17 2.63065 14.8625 3.30823 15.2013L9.10612 18.1002L9.41463 17.4831Z"
                fill="currentColor"
            />
        </svg>
    ');
@endphp
@section('content')
<div x-data="draftPengajuan({
				pengajuanId: {{ $pengajuan->id }},
        alasan: @js($pengajuan->alasan_pengajuan),
        items: @js($pengajuan->details->map(fn($d) => [
            'id' => $d->id,
            'barang_id' => $d->barang_id,
            'nama_barang_diajukan' => $d->nama_barang_diajukan,
            'jumlah_diajukan' => $d->jumlah_diajukan,
            'estimasi_harga_satuan' => (float) $d->estimasi_harga_satuan,
            'subtotal' => $d->jumlah_diajukan * $d->estimasi_harga_satuan,
        ])),
    })"
>
    <x-common.component-card title="Form Pengajuan Barang">

        <div class="flex justify-between items-baseline mb-1">
            <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $pengajuan->kode_pengajuan }}</span>
            <span class="text-xs text-amber-600">draft — belum diajukan</span>
        </div>

        <div x-show="errorMessage" x-cloak class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>
        <div x-show="successMessage" x-cloak class="mb-3 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700" x-text="successMessage"></div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Alasan Pengajuan
            </label>
            <input
                type="text"
                x-model="alasan"
                placeholder="Contoh: laptop lab komputer rusak, stok pengganti kosong"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            />
        </div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Barang
            </label>
					<div class="mb-4">
							<label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
									Nama Barang
							</label>
							<input
									type="text"
									x-model="NamaBarang"
									placeholder="Contoh: Laptop Acer E5-476"
									class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
							/>
					</div>

        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jumlah Diajukan
                </label>
                <input
                    type="number"
                    x-model.number="selectedJumlah"
                    min="1"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                />
            </div>

        </div>

        <div class="mb-5">
            <x-ui.button size="sm" variant="primary" type="button" @click="addItem" x-bind:disabled="isSaving">
                <span x-show="!isSaving">Tambah ke Daftar</span>
                <span x-show="isSaving" x-cloak>Menyimpan...</span>
            </x-ui.button>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1.5" x-text="`Daftar barang diajukan (${items.length})`"></div>

       <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
    <template x-if="items.length === 0">
        <div class="p-6 text-center text-sm text-gray-400">Belum ada barang yang dipilih.</div>
    </template>

    <template x-if="items.length > 0">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-3.5 py-2.5">No</th>
                    <th class="px-3.5 py-2.5">Nama Barang</th>
                    <th class="px-3.5 py-2.5 text-center">Jumlah Diajukan</th>
                    <th class="px-3.5 py-2.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="(item, index) in items" :key="item.id">
                    <tr>
											<td class="px-3.5 py-2.5 text-gray-500 dark:text-gray-400" x-text="index + 1"></td>
                        <td class="px-3.5 py-2.5 text-gray-800 dark:text-white/90" x-text="item.nama_barang_diajukan"></td>
                        <td class="px-3.5 py-2.5 text-center text-gray-600 dark:text-gray-300" x-text="item.jumlah_diajukan"></td>
                        <td class="px-3.5 py-2.5 text-right">
                            <button
                                type="button"
                                @click="removeItem(item.id)"
                                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 hover:text-error-500"
                                aria-label="Hapus item"
                            >
                                <i class="ti ti-trash text-base"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </template>
</div>

										<div class="flex gap-2 mt-4">

											<a href="{{ route('pengajuan.index') }}">
												<x-ui.button size="sm" variant="secondary">Kembali</x-ui.button>
											</a>
						<x-ui.button size="sm" variant="primary" type="button" @click="verifikasi" x-bind:disabled="isSubmitting || items.length === 0">
								<span x-show="!isSubmitting">Ajukan</span>
								<span x-show="isSubmitting" x-cloak>Memproses...</span>
						</x-ui.button>
										</div>



    </x-common.component-card>
</div>

<script>
function draftPengajuan({ pengajuanId, alasan, items}) {
    return {
        pengajuanId,
        alasan,
        items,
        NamaBarang: '',
        selectedJumlah: 1,
        isSaving: false,
        isSubmitting: false,
        errorMessage: '',
        successMessage: '',

        get totalEstimasi() {
            return this.items.reduce((sum, i) => sum + i.subtotal, 0);
        },

				 formatSelectedHarga(event) {
            const raw = event.target.value.replace(/\D/g, '');
            this.selectedHarga = raw ? Number(raw) : 0;
            this.selectedHargaDisplay = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
        },

        onBarangChange() {
            const barang = this.dataBarang.find(b => b.id == this.selectedBarangId);
            if (barang && barang.harga_barang) {
                this.selectedHarga = barang.harga_barang;
                this.selectedHargaDisplay = new Intl.NumberFormat('id-ID').format(barang.harga_barang);
            }
        },

        clearMessages() {
            this.errorMessage = '';
            this.successMessage = '';
        },

        async addItem() {
    this.clearMessages();

    if (!this.NamaBarang || !this.selectedJumlah || this.selectedJumlah < 1) {
        this.errorMessage = 'Pilih barang dan isi jumlah terlebih dahulu';
        return;
    }

    this.isSaving = true;

    try {
        const res = await fetch(`/pengajuan/${this.pengajuanId}/items`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                nama_barang_diajukan: this.NamaBarang,
                jumlah_diajukan: this.selectedJumlah
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            this.errorMessage = data.message || 'Gagal menambahkan barang';
            return;
        }

        // langsung push, karena tiap submit selalu jadi row baru (backend pakai create(), bukan updateOrCreate())
        this.items.push(data.detail);

        this.NamaBarang = '';
        this.selectedJumlah = 1;
        this.selectedHarga = 0;
        this.successMessage = 'Barang ditambahkan';
        setTimeout(() => this.successMessage = '', 2000);

    } catch (e) {
        this.errorMessage = 'Terjadi kesalahan jaringan';
    } finally {
        this.isSaving = false;
    }
},

        async removeItem(detailId) {
            this.clearMessages();

            try {
                const res = await fetch(`/pengajuan/${this.pengajuanId}/items/${detailId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                if (!res.ok) {
                    this.errorMessage = 'Gagal menghapus barang';
                    return;
                }

                this.items = this.items.filter(i => i.id !== detailId);

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            }
        },

        async verifikasi() {
            this.clearMessages();

            if (!this.alasan.trim()) {
                this.errorMessage = 'Isi alasan pengajuan terlebih dahulu';
                return;
            }

            if (this.items.length === 0) {
                this.errorMessage = 'Belum ada barang yang diajukan';
                return;
            }

            this.isSubmitting = true;

            try {
                const res = await fetch(`/pengajuan/${this.pengajuanId}/verifikasi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ alasan_pengajuan: this.alasan }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.errorMessage = data.message || 'Gagal verifikasi pengajuan';
                    return;
                }

                window.location.href = data.redirect;

            } catch (e) {
                this.errorMessage = 'Terjadi kesalahan jaringan';
            } finally {
                this.isSubmitting = false;
            }
        },
    };
}
</script>
@endsection
