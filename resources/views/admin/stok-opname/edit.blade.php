@extends('layouts.app')
@php $currentPageTitle = 'Input Stok Opname'; @endphp
@section('content')

<div x-data="opnameForm({ items: @js($stokOpname->items->map(fn($i) => [
        'item_id' => $i->id,
        'nama_barang' => $i->barang->nama_barang,
        'satuan' => $i->barang->satuan,
        'stok_sistem' => $i->stok_sistem,
        'stok_fisik' => $i->stok_fisik ?? $i->stok_sistem,
        'keterangan' => $i->keterangan ?? '',
    ])) })" class="max-w-3xl mx-auto">

    <x-common.component-card title="Input Stok Opname ">

        @if($stokOpname->status === 'dibatalkan_spv')
            <div class="mb-4 rounded-lg bg-amber-50 border border-amber-200 px-4 py-2.5 text-sm text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                <strong>Dibatalkan Supervisor:</strong> {{ $stokOpname->catatan_cancel }}
            </div>
        @endif

        <div x-show="errorMessage" x-cloak class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.stok-opname.submit', $stokOpname->id) }}">
            @csrf

            <div class="grid grid-cols-2 gap-3 mb-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        No. BAST <span class="text-error-500">*</span>
                    </label>
                    <input type="text" name="no_bast" value="{{ old('no_bast', $stokOpname->no_bast) }}"
                        placeholder="Contoh: 001/BAST-OPNAME/2026"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>
                <div>
								<label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
										Tanggal BAST <span class="text-error-500">*</span>
								</label>
								<input type="text"
										name="tanggal_bast"
										x-model="tanggalBast"
										x-init="flatpickr($el, {
												dateFormat: 'Y-m-d',
												onChange: function(selectedDates, dateStr) {
														tanggalBast = dateStr;
												}
										})"
										placeholder="Pilih tanggal..."
										class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
								</div>
						</div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800 mb-5">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-white/[0.02]">
                        <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Barang</th>
                            <th class="px-4 py-3">Stok Sistem</th>
                            <th class="px-4 py-3">Stok Fisik</th>
                            <th class="px-4 py-3">Selisih</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <template x-for="(item, index) in items" :key="item.item_id">
                            <tr>
                                <td class="px-4 py-3" x-text="index + 1"></td>
                                <td class="px-4 py-3" x-text="`${item.nama_barang} (${item.satuan})`"></td>
                                <td class="px-4 py-3 text-gray-500" x-text="item.stok_sistem"></td>
                                <td class="px-4 py-3">
                                    <input type="hidden" :name="`items[${index}][item_id]`" :value="item.item_id">
                                    <input type="number" min="0" x-model.number="item.stok_fisik"
                                        :name="`items[${index}][stok_fisik]`"
                                        @input="hitungSelisih(index)"
                                        class="h-9 w-24 rounded-lg border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white/90">
                                </td>
                                <td class="px-4 py-3">
                                    <span :class="item.stok_fisik - item.stok_sistem !== 0 ? 'text-error-500 font-medium' : 'text-gray-400'"
                                        x-text="item.stok_fisik - item.stok_sistem"></span>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" x-model="item.keterangan" :name="`items[${index}][keterangan]`"
                                        x-show="item.stok_fisik - item.stok_sistem !== 0"
                                        placeholder="Wajib diisi jika ada selisih"
                                        class="h-9 w-full min-w-[180px] rounded-lg border border-gray-300 bg-transparent px-2 text-sm dark:border-gray-700 dark:text-white/90">
                                </td>

                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan (opsional)</label>
                <textarea name="catatan" rows="2"
                    class="w-full rounded-lg border border-gray-300 bg-transparent p-3 text-sm dark:border-gray-700 dark:text-white/90">{{ old('catatan', $stokOpname->catatan) }}</textarea>
            </div>

            <div class="flex gap-2">
                <x-ui.button size="md" variant="primary" type="submit" @click="validateBeforeSubmit">Ajukan</x-ui.button>
                <a href="{{ route('admin.stok-opname.index') }}">
                    <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
                </a>
            </div>
        </form>
    </x-common.component-card>
</div>

@verbatim
<script>
function opnameForm({ items }) {
    return {
        items,
        errorMessage: '',

        hitungSelisih(index) {
            // reaktivitas otomatis via x-model, method ini cuma trigger re-render kalau perlu
        },

        validateBeforeSubmit(e) {
            this.errorMessage = '';
            const adaSelisihTanpaKeterangan = this.items.some(item => {
                const selisih = (item.stok_fisik ?? 0) - item.stok_sistem;
                return selisih !== 0 && !item.keterangan?.trim();
            });

            if (adaSelisihTanpaKeterangan) {
                e.preventDefault();
                this.errorMessage = 'Isi keterangan untuk semua barang yang memiliki selisih.';
            }
        },
    };
}
</script>
@endverbatim
@endsection
