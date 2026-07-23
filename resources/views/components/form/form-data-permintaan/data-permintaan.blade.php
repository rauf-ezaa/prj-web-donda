<div x-data="draftPermintaan({
        permintaanId: {{ $permintaan->id }},
        keperluan: @js($permintaan->keperluan),
        items: @js($permintaan->details->map(fn($d) => [
            'id' => $d->id,
            'barang_id' => $d->barang_id,
            'nama_barang' => $d->barang->nama_barang,
            'jumlah' => $d->jumlah_diminta,
        ])),
        dataBarang: @js($dataBarang),
    })"
>
    <x-common.component-card title="Form Input Permintaan Barang">

        <div class="flex justify-between items-baseline mb-1">
            <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $permintaan->kode_permintaan }}</span>
            <span class="text-xs text-amber-600">draft — belum diajukan</span>
        </div>

        <div x-show="errorMessage" x-cloak class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>
        <div x-show="successMessage" x-cloak class="mb-3 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700" x-text="successMessage"></div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Keperluan
            </label>
            <input
                type="text"
                x-model="keperluan"
                placeholder="Masukkan keperluan permintaan"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            />
        </div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Nama Barang
            </label>
            <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                <select
                    x-model="selectedBarangId"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true"
                >
                    <option value="">Pilih Barang</option>
                    <template x-for="barang in dataBarang" :key="barang.id">
                        <option :value="barang.id" x-text="`${barang.nama_barang} (stok tersedia: ${barang.stok_tersedia})`"></option>
                    </template>
                </select>
                <span class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </div>
        </div>

        <div class="mb-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Jumlah Permintaan Barang
            </label>
            <input
                type="number"
                x-model.number="selectedJumlah"
                min="1"
                placeholder="Masukkan jumlah permintaan"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            />
        </div>

        <div class="mb-5">
            <x-ui.button size="sm" variant="primary" type="button" @click="addItem" :disabled="isSaving">
                <span x-show="!isSaving">Tambah ke Daftar</span>
                <span x-show="isSaving" x-cloak>Menyimpan...</span>
            </x-ui.button>
        </div>

        <div class="text-sm text-gray-500 dark:text-gray-400 mb-1.5" x-text="`Daftar barang (${items.length})`"></div>

        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
            <template x-if="items.length === 0">
                <div class="p-6 text-center text-sm text-gray-400">
                    Belum ada barang yang ditambahkan.
                </div>
            </template>
            <template x-for="(item, index) in items" :key="item.id">
                <div class="flex items-center px-3.5 py-2.5" :class="{ 'border-b border-gray-200 dark:border-gray-700': index < items.length - 1 }">
                    <div class="flex-1">
                        <p class="text-sm m-0 text-gray-800 dark:text-white/90" x-text="item.nama_barang"></p>
                        <p class="text-xs text-gray-400 m-0" x-text="`Qty: ${item.jumlah}`"></p>
                    </div>
                    <button
                        type="button"
                        @click="removeItem(item.id)"
                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-error-500"
                        aria-label="Hapus item"
                    >
                        <i class="ti ti-trash text-base"></i>
                    </button>
                </div>
            </template>
        </div>

        <x-ui.button size="sm" variant="primary" type="button" @click="verifikasi" :disabled="isSubmitting || items.length === 0">
            <span x-show="!isSubmitting">Verifikasi dan Ajukan</span>
            <span x-show="isSubmitting" x-cloak>Memproses...</span>
        </x-ui.button>

    </x-common.component-card>
</div>

<script>
function draftPermintaan({ permintaanId, keperluan, items, dataBarang }) {
    return {
        permintaanId,
        keperluan,
        items,
        dataBarang,
        selectedBarangId: '',
        selectedJumlah: 1,
        isSaving: false,
        isSubmitting: false,
        errorMessage: '',
        successMessage: '',

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

            this.isSaving = true;

            try {
                const res = await fetch(`/permintaan/${this.permintaanId}/items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        barang_id: this.selectedBarangId,
                        jumlah: this.selectedJumlah,
                    }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.errorMessage = data.message || 'Gagal menambahkan barang';
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
                const res = await fetch(`/permintaan/${this.permintaanId}/items/${detailId}`, {
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

            this.isSubmitting = true;

            try {
                const res = await fetch(`/permintaan/${this.permintaanId}/verifikasi`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ keperluan: this.keperluan }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.errorMessage = data.message || 'Gagal verifikasi permintaan';
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
