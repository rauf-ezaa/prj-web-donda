@extends('layouts.app')
@php $currentPageTitle = 'Edit Saldo Awal'; @endphp
@section('content')

<div x-data="saldoAwalForm({
        dataBarang: @js($dataBarang),
        existingItems: @js($saldoAwal->items->map(fn($i) => [
            'barang_id' => $i->barang_id,
            'qty' => $i->qty,
        ])),
    })" class="max-w-2xl mx-auto">
    <x-common.component-card title="Edit Saldo Awal — {{ $saldoAwal->no_transaksi }}">

        <div class="mb-4 rounded-lg bg-amber-50 border border-amber-200 px-4 py-2.5 text-sm text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
            Transaksi ini masih menunggu verifikasi supervisor. Kamu bisa mengubah datanya sebelum diverifikasi.
        </div>

        <div x-show="errorMessage" x-cloak class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500" x-text="errorMessage"></div>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('saldo-awal.update', $saldoAwal->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Tanggal Pencatatan <span class="text-error-500">*</span>
                </label>
                <input type="date" name="tanggal_pencatatan" value="{{ old('tanggal_pencatatan', $saldoAwal->tanggal_pencatatan->format('Y-m-d')) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-400 mb-3">Daftar Barang</p>

                <template x-for="(row, index) in rows" :key="index">
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800 mb-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Barang</label>
                                <select x-model="row.barang_id" :name="`items[${index}][barang_id]`"
                                    class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="">Pilih Barang</option>
                                    <template x-for="barang in dataBarang" :key="barang.id">
                                        <option :value="barang.id" x-text="`${barang.nama_barang} (${barang.satuan})`"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Qty</label>
                                <input type="number" min="1" x-model.number="row.qty" :name="`items[${index}][qty]`"
                                    class="h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            </div>
                        </div>

                        <button type="button" @click="removeRow(index)" x-show="rows.length > 1"
                            class="mt-2 text-xs text-error-500 hover:underline">
                            Hapus baris
                        </button>
                    </div>
                </template>

                <x-ui.button size="sm" variant="secondary" type="button" @click="addRow">
                    + Tambah Barang
                </x-ui.button>
            </div>

            <div class="mb-5">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan (opsional)</label>
                <textarea name="catatan" rows="2"
                    class="w-full rounded-lg border border-gray-300 bg-transparent p-3 text-sm dark:border-gray-700 dark:text-white/90">{{ old('catatan', $saldoAwal->catatan) }}</textarea>
            </div>

            <div class="flex gap-2">
                <x-ui.button size="md" variant="primary" type="submit" @click="validateBeforeSubmit">Simpan Perubahan</x-ui.button>
                <a href="{{ route('saldo-awal.show', $saldoAwal->id) }}">
                    <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
                </a>
            </div>
        </form>
    </x-common.component-card>
</div>

@verbatim
<script>
function saldoAwalForm({ dataBarang, existingItems }) {
    return {
        dataBarang,
        rows: existingItems && existingItems.length > 0
            ? existingItems.map(i => ({ barang_id: i.barang_id, qty: i.qty }))
            : [{ barang_id: '', qty: 1 }],
        errorMessage: '',

        addRow() {
            this.rows.push({ barang_id: '', qty: 1 });
        },

        removeRow(index) {
            this.rows.splice(index, 1);
        },

        validateBeforeSubmit(e) {
            this.errorMessage = '';
            const invalid = this.rows.some(r => !r.barang_id || !r.qty || r.qty < 1);
            if (invalid) {
                e.preventDefault();
                this.errorMessage = 'Pastikan semua baris barang sudah dipilih dan qty diisi minimal 1.';
            }
        },
    };
}
</script>
@endverbatim
@endsection
