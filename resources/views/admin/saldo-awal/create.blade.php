@extends('layouts.app')
@php $currentPageTitle = 'Input Saldo Awal'; @endphp
@section('content')

<div x-data="saldoAwalForm({ dataBarang: @js($dataBarang), existingItems: [] })" class="max-w-2xl mx-auto">
    <x-common.component-card title="Input Saldo Awal Barang">

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
            Catat stok barang yang sudah ada secara fisik sebelum sistem ini digunakan. Barang yang sudah pernah dicatat saldo awalnya tidak akan muncul di daftar pilihan.
        </p>

        <div x-show="errorMessage" x-cloak class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

            <form method="POST" action="{{ route('saldo-awal.store') }}">
                @csrf
														<div class="mb-4">
										<label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
												Tanggal Pencatatan <span class="text-error-500">*</span>
										</label>

										<input
												type="text"
												id="tanggal_pencatatan"
												name="tanggal_pencatatan"
												value="{{ old('tanggal_pencatatan', date('Y-m-d')) }}"
												placeholder="Pilih tanggal"
												class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
								</div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-3">Tambah Barang</p>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Barang</label>
                            <select x-model="selectedBarangId"
                                class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Pilih Barang</option>
                                <template x-for="barang in availableBarang" :key="barang.id">
                                    <option :value="barang.id" x-text="`${barang.nama_barang} (${barang.satuan})`"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Qty</label>
                            <input type="number" min="1" x-model.number="selectedQty"
                                class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>
                    </div>

                    <x-ui.button size="sm" variant="secondary" type="button" @click="addItem">
                        + Tambah ke Daftar
                    </x-ui.button>

                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-5 mb-1.5" x-text="`Daftar barang (${items.length})`"></div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <template x-if="items.length === 0">
                            <div class="p-6 text-center text-sm text-gray-400">Belum ada barang yang ditambahkan.</div>
                        </template>

                        <template x-if="items.length > 0">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-white/[0.02]">
                                    <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                        <th class="px-3.5 py-2.5">No</th>
                                        <th class="px-3.5 py-2.5">Nama Barang</th>
                                        <th class="px-3.5 py-2.5 text-center">Qty</th>
                                        <th class="px-3.5 py-2.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(item, index) in items" :key="item.barang_id">
                                        <tr>
                                            <td class="px-3.5 py-2.5 text-gray-500 dark:text-gray-400" x-text="index + 1"></td>
                                            <td class="px-3.5 py-2.5 text-gray-800 dark:text-white/90" x-text="`${item.nama_barang} (${item.satuan})`"></td>
                                            <td class="px-3.5 py-2.5 text-center text-gray-600 dark:text-gray-300" x-text="item.qty"></td>
                                            <td class="px-3.5 py-2.5 text-right">
                                                <button type="button" @click="removeItem(index)"
                                                    class="w-8 h-8 inline-flex items-center justify-center text-gray-400 hover:text-error-500"
                                                    aria-label="Hapus item">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            </td>

                                            <!-- hidden inputs supaya ikut ter-submit bersama form -->
                                            <input type="hidden" :name="`items[${index}][barang_id]`" :value="item.barang_id" />
                                            <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty" />
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2"
                        class="w-full rounded-lg border border-gray-300 bg-transparent p-3 text-sm dark:border-gray-700 dark:text-white/90">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-2">
                    <x-ui.button size="md" variant="primary" type="submit" @click="validateBeforeSubmit">Ajukan</x-ui.button>
                    <a href="{{ route('saldo-awal.index') }}">
                        <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
                    </a>
                </div>
            </form>
    </x-common.component-card>
</div>

@verbatim
<script>
function saldoAwalForm({ dataBarang }) {
    return {
        dataBarang,
        items: [],
        selectedBarangId: '',
        selectedQty: 1,
        errorMessage: '',

        // barang yang sudah ada di daftar disembunyikan dari pilihan, biar tidak dobel
        get availableBarang() {
            return this.dataBarang.filter(b => !this.items.some(i => i.barang_id == b.id));
        },

        addItem() {
            this.errorMessage = '';

            if (!this.selectedBarangId || !this.selectedQty || this.selectedQty < 1) {
                this.errorMessage = 'Pilih barang dan isi qty minimal 1.';
                return;
            }

            const barang = this.dataBarang.find(b => b.id == this.selectedBarangId);
            if (!barang) return;

            this.items.push({
                barang_id: barang.id,
                nama_barang: barang.nama_barang,
                satuan: barang.satuan,
                qty: this.selectedQty,
            });

            this.selectedBarangId = '';
            this.selectedQty = 1;
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        validateBeforeSubmit(e) {
            this.errorMessage = '';
            if (this.items.length === 0) {
                e.preventDefault();
                this.errorMessage = 'Tambahkan minimal satu barang ke daftar sebelum mengajukan.';
            }
        },
    };
}

document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#tanggal_pencatatan", {
        dateFormat: "Y-m-d",
        allowInput: true,
        defaultDate: "{{ old('tanggal_pencatatan', date('Y-m-d')) }}"
    });
});

</script>

@endverbatim
@endsection
