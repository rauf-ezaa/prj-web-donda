@extends('layouts.app')

@php
$currentPageTitle = 'Edit Pembelian Barang';
@endphp

@section('content')

<div
    x-data="pembelianForm({
        dataBarang: @js($dataBarang),
        existingItems: @js($existingItems)
    })"
    class="max-w-2xl mx-auto">

    <x-common.component-card
        title="Edit Pembelian Barang — {{ $pembelian->no_transaksi }}">

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
            action="{{ route('pembelian.update', $pembelian->id) }}">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3 mb-4">

                <div>
                    <label class="mb-1.5 block text-sm font-medium">
                        Nama Supplier
                    </label>

                    <input
                        type="text"
                        name="nama_supplier"
                        value="{{ old('nama_supplier', $pembelian->nama_supplier) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm" />
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium">
                        Tanggal Diterima
                    </label>

                    <input
                        type="text"
                        id="tanggal_diterima"
                        name="tanggal_diterima"
                        value="{{ old('tanggal_diterima', $pembelian->tanggal_diterima->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm" />
                </div>

            </div>

            <div class="border-t border-gray-200 pt-4 mb-4">

                <p class="text-sm font-medium mb-3">
                    Tambah Barang Masuk
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

                <div class="mb-3">

                    <label class="mb-1 block text-xs text-gray-500">
                        Deskripsi
                    </label>

                    <input
                        type="text"
                        x-model="selectedDeskripsi"
                        class="h-10 w-full rounded-lg border border-gray-300 px-3 text-sm" />

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
                    x-text="`Daftar barang masuk (${items.length})`">
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

                                    <th class="px-3 py-2 text-left">
                                        Deskripsi
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

                                        <td class="px-3 py-2"
                                            x-text="index+1">
                                        </td>

                                        <td
                                            class="px-3 py-2"
                                            x-text="`${item.nama_barang} (${item.satuan})`">
                                        </td>

                                        <td
                                            class="px-3 py-2 text-center"
                                            x-text="item.qty">
                                        </td>

                                        <td
                                            class="px-3 py-2"
                                            x-text="item.deskripsi || '-'">
                                        </td>

                                        <td class="px-3 py-2 text-right">

                                            <button
                                                type="button"
                                                @click="removeItem(index)"
                                                class="text-error-500">

                                                Hapus

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

                                        <input
                                            type="hidden"
                                            :name="`items[${index}][deskripsi]`"
                                            :value="item.deskripsi">

                                    </tr>

                                </template>

                            </tbody>

                        </table>

                    </template>

                </div>

            </div>

            <div class="mb-5">

                <label class="mb-1.5 block text-sm font-medium">
                    Catatan
                </label>

                <textarea
                    name="catatan"
                    rows="2"
                    class="w-full rounded-lg border border-gray-300 p-3 text-sm">{{ old('catatan', $pembelian->catatan) }}</textarea>

            </div>

            <div class="flex gap-2">

                <x-ui.button
                    size="md"
                    variant="primary"
                    type="submit"
                    @click="validateBeforeSubmit">

                    Simpan Perubahan

                </x-ui.button>

            </div>

        </form>

    </x-common.component-card>

</div>

<script>
function pembelianForm({
    dataBarang,
    existingItems = []
}) {

    return {

        dataBarang,

        items: existingItems,

        selectedBarangId: '',
        selectedQty: 1,
        selectedDeskripsi: '',

        errorMessage: '',

        get availableBarang() {

            return this.dataBarang.filter(
                b => !this.items.some(
                    i => i.barang_id == b.id
                )
            );

        },

        addItem() {

            if (
                !this.selectedBarangId ||
                this.selectedQty < 1
            ) {
                return;
            }

            const barang =
                this.dataBarang.find(
                    b => b.id == this.selectedBarangId
                );

            this.items.push({

                barang_id: barang.id,

                nama_barang: barang.nama_barang,

                satuan: barang.satuan,

                qty: this.selectedQty,

                deskripsi: this.selectedDeskripsi

            });

            this.selectedBarangId = '';
            this.selectedQty = 1;
            this.selectedDeskripsi = '';

        },

        removeItem(index) {

            this.items.splice(index, 1);

        },

        validateBeforeSubmit(e) {

            if (this.items.length === 0) {

                e.preventDefault();

                this.errorMessage =
                    'Minimal satu barang harus ada.';

            }

        }

    };
}

document.addEventListener('DOMContentLoaded', function () {

    flatpickr("#tanggal_diterima", {
        dateFormat: "Y-m-d",
        allowInput: true
    });

});
</script>

@endsection
