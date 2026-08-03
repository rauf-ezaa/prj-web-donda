@extends('layouts.app')

@php
$currentPageTitle = 'Edit Saldo Awal';
@endphp

@section('content')

<div
    x-data="saldoAwalForm({
        dataBarang: @js($dataBarang),
        existingItems: @js($existingItems)
    })"
    class="max-w-2xl mx-auto">

    <x-common.component-card
        title="Edit Saldo Awal — {{ $saldoAwal->no_transaksi }}">

        <div class="mb-4 rounded-lg bg-amber-50 border border-amber-200 px-4 py-2.5 text-sm text-amber-700">
            Transaksi masih menunggu verifikasi supervisor.
        </div>

        <div
            x-show="errorMessage"
            x-cloak
            class="mb-3 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500"
            x-text="errorMessage">
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('saldo-awal.update', $saldoAwal->id) }}">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="mb-1.5 block text-sm font-medium">
                    Tanggal Pencatatan
                    <span class="text-error-500">*</span>
                </label>

                <input
                    type="text"
                    id="tanggal_pencatatan"
                    name="tanggal_pencatatan"
                    value="{{ old('tanggal_pencatatan', $saldoAwal->tanggal_pencatatan->format('Y-m-d')) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm" />

            </div>

            <div class="border-t border-gray-200 pt-4 mb-4">

                <p class="text-sm font-medium mb-3">
                    Tambah Barang
                </p>

                <div class="grid grid-cols-2 gap-3 mb-3">

                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Barang
                        </label>

                        <select
                            x-model="selectedBarangId"
                            class="h-10 w-full rounded-lg border border-gray-300 px-3 text-sm">

                            <option value="">
                                Pilih Barang
                            </option>

                            <template
                                x-for="barang in availableBarang"
                                :key="barang.id">

                                <option
                                    :value="barang.id"
                                    x-text="`${barang.nama_barang} (${barang.satuan})`">
                                </option>

                            </template>

                        </select>

                    </div>

                    <div>

                        <label class="mb-1 block text-xs text-gray-500">
                            Qty
                        </label>

                        <input
                            type="number"
                            min="1"
                            x-model.number="selectedQty"
                            class="h-10 w-full rounded-lg border border-gray-300 px-3 text-sm" />

                    </div>

                </div>

                <x-ui.button
                    size="sm"
                    variant="secondary"
                    type="button"
                    @click="addItem">

                    + Tambah ke Daftar

                </x-ui.button>

                <div
                    class="text-sm text-gray-500 mt-5 mb-1.5"
                    x-text="`Daftar barang (${items.length})`">
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">

                    <template x-if="items.length === 0">

                        <div class="p-6 text-center text-sm text-gray-400">
                            Belum ada barang.
                        </div>

                    </template>

                    <template x-if="items.length > 0">

                        <table class="min-w-full text-sm">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-3 py-2 text-left">
                                        No
                                    </th>

                                    <th class="px-3 py-2 text-left">
                                        Barang
                                    </th>

                                    <th class="px-3 py-2 text-center">
                                        Qty
                                    </th>

                                    <th class="px-3 py-2 text-right">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <template
                                    x-for="(item,index) in items"
                                    :key="item.barang_id">

                                    <tr>

                                        <td
                                            class="px-3 py-2"
                                            x-text="index + 1">
                                        </td>

                                        <td
                                            class="px-3 py-2"
                                            x-text="`${item.nama_barang} (${item.satuan})`">
                                        </td>

                                        <td
                                            class="px-3 py-2 text-center"
                                            x-text="item.qty">
                                        </td>

                                        <td class="px-3 py-2 text-right">

                                            <button
                                                type="button"
                                                @click="removeItem(index)"
                                                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 hover:text-error-500">

                                                <i class="ti ti-trash text-base"></i>

                                            </button>

                                        </td>

                                        <input
                                            type="hidden"
                                            :name="`items[${index}][barang_id]`"
                                            :value="item.barang_id">

                                        <input
                                            type="hidden"
                                            :name="`items[${index}][qty]`"
                                            :value="item.qty">

                                    </tr>

                                </template>

                            </tbody>

                        </table>

                    </template>

                </div>

            </div>

            <div class="mb-5">

                <label class="mb-1.5 block text-sm font-medium">
                    Catatan (opsional)
                </label>

                <textarea
                    name="catatan"
                    rows="2"
                    class="w-full rounded-lg border border-gray-300 p-3 text-sm">{{ old('catatan', $saldoAwal->catatan) }}</textarea>

            </div>

            <div class="flex gap-2">

                <x-ui.button
                    size="md"
                    variant="primary"
                    type="submit"
                    @click="validateBeforeSubmit">

                    Simpan Perubahan

                </x-ui.button>

                <a href="{{ route('saldo-awal.show', $saldoAwal->id) }}">
                    <x-ui.button
                        size="md"
                        variant="secondary"
                        type="button">

                        Batal

                    </x-ui.button>
                </a>

            </div>

        </form>

    </x-common.component-card>

</div>

<script>
function saldoAwalForm({
    dataBarang,
    existingItems = []
}) {

    return {

        dataBarang,

        items: existingItems,

        selectedBarangId: '',
        selectedQty: 1,

        errorMessage: '',

        get availableBarang() {

            return this.dataBarang.filter(
                b => !this.items.some(
                    i => i.barang_id == b.id
                )
            );

        },

        addItem() {

            this.errorMessage = '';

            if (
                !this.selectedBarangId ||
                !this.selectedQty ||
                this.selectedQty < 1
            ) {

                this.errorMessage =
                    'Pilih barang dan isi qty minimal 1.';

                return;

            }

            const barang =
                this.dataBarang.find(
                    b => b.id == this.selectedBarangId
                );

            if (!barang) return;

            this.items.push({

                barang_id: barang.id,
                nama_barang: barang.nama_barang,
                satuan: barang.satuan,
                qty: this.selectedQty

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

                this.errorMessage =
                    'Tambahkan minimal satu barang ke daftar sebelum mengajukan.';

            }

        }

    };
}

document.addEventListener('DOMContentLoaded', function () {

    flatpickr("#tanggal_pencatatan", {
        dateFormat: "Y-m-d",
        allowInput: true
    });

});
</script>

@endsection
