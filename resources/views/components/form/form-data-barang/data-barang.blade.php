<div x-data="{
    kibId: '',
    kib_id: '',

		hargaDisplay: '',
		hargaRaw: 0,
    dataKategori: @js($dataKategori),

    get filteredKategori() {
        return this.dataKategori.filter(
            item => item.kib_id == this.kibId
        );
    }
}">

<form action="{{route('data-barang.store')}}" method="POST">
    @CSRF
<x-common.component-card title="Form Input Data Barang">
    <!-- Elements -->
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nama Barang
        </label>
        <input type="text" placeholder="Masukkan Nama Barang" name="nama_barang" value="{{ old('nama_barang') }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

            @error('nama_barang')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
    </div>

		 <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
           Deskripsi Barang
        </label>
        <input type="text" placeholder="Masukkan Deskripsi Barang" name="description" value="{{ old('description') }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />

            @error('description')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
    </div>

    <!-- Elements -->
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Harga Barang
        </label>
        <input type="text"
						placeholder="Masukkan Harga Barang"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
        		x-model="hargaDisplay"
        		@input="
            hargaRaw = $event.target.value.replace(/\D/g, '');
            hargaDisplay = hargaRaw ? new Intl.NumberFormat('id-ID').format(hargaRaw) : '';
        		"
						/>
         @error('harga_barang')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
		 <input type="hidden" name="harga_barang" :value="hargaRaw">
    </div>


    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
           Klasifikasi Aset
        </label>
        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
            <select
                 select
                  x-model="kibId"
                name="klasifikasi_kib"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                -- Pilih Jenis Aset --
                </option>

              @foreach ($dataKategori->unique('kib_id') as $data)
                    <option value="{{ $data['kib_id'] }}">
                        {{ $data['kode_kib'] }}
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
            @error('klasifikasi_kib')
                <p class="mt-1 text-sm text-error-500">
                    {{ $message }}
                </p>
            @enderror
    </div>

     <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
          Kategori Aset
        </label>
        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
            <select
               x-model="kib_id"
                name="kategori_id"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">

                <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                -- Pilih Jenis Aset --
                </option>

                    <template x-for="item in filteredKategori" :key="item.id">
                        <option
                            :value="item.id"
                            x-text="item.nama_kategori"
                        ></option>
                    </template>

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
            @error('stok_tersedia')
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
