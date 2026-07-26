@php $barang = $barang ?? null; @endphp

<div class="space-y-4">
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nama Barang <span class="text-error-500">*</span>
        </label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang ?? '') }}"
            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Merk/Spesifikasi</label>
        <input type="text" name="description" value="{{ old('description', $barang->description ?? '') }}"
            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Klasifikasi <span class="text-error-500">*</span>
            </label>
            <select name="klasifikasi_kib"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Pilih Klasifikasi</option>
                @foreach($kibList as $kib)
                    <option value="{{ $kib->id }}" @selected(old('klasifikasi_kib', $barang->klasifikasi_kib ?? '') == $kib->id)>
                        {{ $kib->klasifikasi }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Satuan <span class="text-error-500">*</span>
            </label>
            <select name="satuan"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                @foreach(\App\Models\Barang::SATUAN_OPTIONS as $satuan)
                    <option value="{{ $satuan }}" @selected(old('satuan', $barang->satuan ?? '') == $satuan)>{{ $satuan }}</option>
                @endforeach
            </select>
        </div>
    </div>



    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Deskripsi</label>
        <textarea name="description" rows="3"
            class="w-full rounded-lg border border-gray-300 bg-transparent p-3 text-sm text-gray-800 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('description', $barang->description ?? '') }}</textarea>
    </div>
</div>
