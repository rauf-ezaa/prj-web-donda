<div x-data="{
    kibId: '',
    kib_id: '',

	dataBarang: @js($dataBarang),
	hargaDisplay: '',
	hargaRaw: 0,

    get filteredKategori() {
        return this.dataKategori.filter(
            item => item.kib_id == this.kibId
        );
    }
}">

<form action="{{route('persediaan.store')}}" method="POST">
    @CSRF
<x-common.component-card title="Form Input Persediaan Barang">
    <!-- Elements -->
    <!-- <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            sadasa
        </label>
        <input type="text" placeholder="Masukkan Nama Barang" name="nama_barang" value="{{ old('nama_barang') }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

            @error('nama_barang')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
    </div> -->

    <!-- Elements -->

		 <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
					Nama Barang
        </label>
        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
            <select
                select
                name="barang_id"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">

								<option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
										-- Pilih Barang --
                </option>

								@foreach($dataBarang as $data)
										<option value="{{$data->id}}" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
												{{$data->nama_barang}}
										</option>
								@endforeach


            </select>
            <span
                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
            @error('barang_id')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Harga Barang per Satuan
        </label>
        <input type="text" placeholder="Masukkan Harga Barang"
				value="{{ old('harga_satuan') }}"
        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
				x-model="hargaDisplay"
        @input="
            hargaRaw = $event.target.value.replace(/\D/g, '');
            hargaDisplay = hargaRaw ? new Intl.NumberFormat('id-ID').format(hargaRaw) : '';
        "
				/>

				@error('harga_satuan')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
		 <input type="hidden" name="harga_satuan" :value="hargaRaw">

    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Jumlah Barang Masuk
        </label>
        <input type="text" placeholder="Masukkan Stock Barang" name="qty" value="{{ old('qty') }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
         @error('qty')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
    </div>

     <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
          Asal Perolehan Dana
        </label>
        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
            <select
                name="asal_dana"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">


								 <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
										--Pilih Asal Dana--
                </option>

                <option value="bos" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
											Dana BOS
                </option>


								<option value="bop" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
											Dana BOP
                </option>


            </select>
            <span
                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
            @error('asal_dana')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
    </div>


    <!-- <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
           Kategori Aset
        </label>
        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
            <select
                name="kategori_aset"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">

                 <option value="">Pilih Kategori Aset</option>



            </select>
            <span
                class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div> -->



    <!-- Elements -->
    <x-ui.button size="sm" variant="primary" type="submit" >Simpan</x-ui.button>
</div>
</x-common.component-card>
</form>
