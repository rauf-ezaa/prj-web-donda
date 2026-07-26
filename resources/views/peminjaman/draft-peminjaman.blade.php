@extends('layouts.app')
@php $currentPageTitle = 'Draft Peminjaman'; @endphp
@section('content')

<div x-data="draftPeminjaman({
        peminjamanId: {{ $peminjaman->id }},
        keperluan: @js($peminjaman->keperluan),
        tanggalPinjam: @js($peminjaman->tanggal_pinjam->format('Y-m-d')),
        tanggalKembali: @js($peminjaman->tanggal_wajib_kembali->format('Y-m-d')),
        items: @js($peminjaman->details->map(fn($d) => [
            'id' => $d->id,
            'barang_id' => $d->barang_id,
            'nama_barang' => $d->barang->nama_barang,
            'qty_pinjam' => $d->qty_pinjam,
        ])),
        dataBarang: @js($dataBarang),
    })"
    class="max-w-2xl mx-auto"
>
    <x-common.component-card title="Form Peminjaman Barang">

        <div class="flex justify-between items-baseline mb-1">
            <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $peminjaman->kode_peminjaman }}</span>
            <span class="text-xs text-amber-600">draft — belum diajukan</span>
        </div>

        <div x-show="errorMessage" x-cloak class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>
        <div x-show="successMessage" x-cloak class="mb-3 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700" x-text="successMessage"></div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keperluan</label>
            <input type="text" x-model="keperluan" placeholder="Contoh: presentasi rapat wali murid"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pinjam</label>
                <input type="date" x-model="tanggalPinjam"  :min="minTanggalPinjam"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Wajib Kembali</label>
                <input type="date" x-model="tanggalKembali"  :min="tanggalPinjam"
                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>
        </div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Barang</label>
            <select x-model="selectedBarangId"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Pilih Barang</option>
                <template x-for="barang in dataBarang" :key="barang.id">
                    <option :value="barang.id" x-text="`${barang.nama_barang} (stok tersedia: ${barang.stok_tersedia})`"></option>
                </template>
            </select>
        </div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Jumlah Pinjam</label>
            <input type="number" x-model.number="selectedJumlah" min="1"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
        </div>

        <div class="mb-5">
            <x-ui.button size="sm" variant="primary" type="button" @click="addItem" x-bind:disabled="isSaving">
                <span x-show="!isSaving">Tambah ke Daftar</span>
                <span x-show="isSaving" x-cloak>Menyimpan...</span>
            </x-ui.button>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1.5" x-text="`Daftar barang (${items.length})`"></div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
            <template x-if="items.length === 0">
                <div class="p-6 text-center text-sm text-gray-400">Belum ada barang yang dipilih.</div>
            </template>
            <template x-for="(item, index) in items" :key="item.id">
                <div class="flex items-center px-3.5 py-2.5" :class="{ 'border-b border-gray-200 dark:border-gray-700': index < items.length - 1 }">
                    <div class="flex-1">
                        <p class="text-sm m-0 text-gray-800 dark:text-white/90" x-text="item.nama_barang"></p>
                        <p class="text-xs text-gray-400 m-0" x-text="`Qty: ${item.qty_pinjam}`"></p>
                    </div>
                    <button type="button" @click="removeItem(item.id)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-error-500" aria-label="Hapus item">
                        <i class="ti ti-trash text-base"></i>
                    </button>
                </div>
            </template>
        </div>



				<div class="flex gap-2 mt-4">
					<x-ui.button size="sm" variant="primary" type="button" @click="verifikasi" x-bind:disabled="isSubmitting || items.length === 0">
							<span x-show="!isSubmitting">Verifikasi dan Ajukan</span>
							<span x-show="isSubmitting" x-cloak>Memproses...</span>
					</x-ui.button>
		@if ($peminjaman->is_editable)
				<form action="{{ route('peminjaman.batalkan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pengajuan ini?')">
						@csrf
						<x-ui.button size="sm" variant="secondary" type="submit">Batalkan Pengajuan</x-ui.button>
				</form>
		@endif

		<a href="{{ url()->previous() }}">
				<x-ui.button size="sm" variant="secondary">Kembali</x-ui.button>
		</a>
	</div>
			</x-common.component-card>
</div>

@verbatim
<script>
function draftPeminjaman({ peminjamanId, keperluan, tanggalPinjam, tanggalKembali, items, dataBarang }) {

    return {
        peminjamanId, keperluan, tanggalPinjam, tanggalKembali, items, dataBarang,
        selectedBarangId: '',
        selectedJumlah: 1,
        isSaving: false,
        isSubmitting: false,
        errorMessage: '',
        successMessage: '',
        minTanggalPinjam: new Date().toISOString().split('T')[0], // hari ini, format YYYY-MM-DD

        clearMessages() {
            this.errorMessage = '';
            this.successMessage = '';
        },

       async addItem() {
    this.clearMessages();

    if (!this.selectedBarangId || !this.selectedJumlah || this.selectedJumlah < 1) {
        this.errorMessage = 'Pilih barang dan isi jumlah terlebih dahulu';
        return;
    }

    // cari data barang yang dipilih dari dataBarang, ini yang tadinya hilang
    const barang = this.dataBarang.find(b => b.id == this.selectedBarangId);
    if (!barang) {
        this.errorMessage = 'Barang tidak ditemukan';
        return;
    }

    if (this.selectedJumlah > barang.stok_tersedia) {
        this.errorMessage = `Jumlah melebihi stok tersedia (tersedia: ${barang.stok_tersedia})`;
        return;
    }

    this.isSaving = true;

    try {
        const res = await fetch(`/peminjaman/${this.peminjamanId}/items`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                barang_id: this.selectedBarangId,
                qty_pinjam: this.selectedJumlah,
            }),
        });

        let data;
        try {
            data = await res.json();
        } catch (err) {
            const txt = await res.text().catch(() => '');
            console.error('addItem JSON parse failed', { status: res.status, txt });
            throw err;
        }

        if (!res.ok) {
            this.errorMessage = data?.message || data?.error || `Gagal menambahkan barang (HTTP ${res.status})`;
            return;
        }

        const existingIndex = this.items.findIndex(i => i.barang_id == data.detail.barang_id);
        if (existingIndex !== -1) {
            this.items[existingIndex] = data.detail;
        } else {
            this.items.push(data.detail);
        }

        this.selectedBarangId = '';
        this.selectedJumlah = 1;
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
                const res = await fetch(`/peminjaman/${this.peminjamanId}/items/${detailId}`, {
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

             if (!this.keperluan.trim()) {
								this.errorMessage = 'Isi keperluan terlebih dahulu';
								return;
						}

						if (this.items.length === 0) {
								this.errorMessage = 'Belum ada barang yang dipilih';
								return;
						}

									const today = new Date().toISOString().split('T')[0];
					if (this.tanggalPinjam < today) {
							this.errorMessage = 'Tanggal pinjam tidak boleh sebelum hari ini';
							return;
					}

					if (this.tanggalKembali < this.tanggalPinjam) {
							this.errorMessage = 'Tanggal wajib kembali tidak boleh sebelum tanggal pinjam';
							return;
					}



            this.isSubmitting = true;

            try {
                const res = await fetch(`/peminjaman/${this.peminjamanId}/verifikasi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        keperluan: this.keperluan,
                        tanggal_pinjam: this.tanggalPinjam,
                        tanggal_wajib_kembali: this.tanggalKembali,
                    }),
                });

                let data;
            try {
                    const txt = await res.text().catch(() => '');
                    console.log('verifikasi raw response', { status: res.status, txt });
                    data = txt ? JSON.parse(txt) : {};
                } catch (err) {
                    console.error('verifikasi parse failed', { status: res.status });
                    throw err;
                }

                if (!res.ok) {
                    this.errorMessage = data?.message || data?.error || `Gagal verifikasi peminjaman (HTTP ${res.status})`;
                    return;
                }

                if (data && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                // bila redirect tidak ada (mis. server tidak mengembalikan JSON),
                // jangan arahkan ke undefined.
                this.errorMessage = 'Server tidak mengembalikan redirect. Periksa response dari endpoint verifikasi.';

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
