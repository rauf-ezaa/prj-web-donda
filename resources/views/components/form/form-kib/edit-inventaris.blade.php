
<form action="{{route('kartu-inventaris-barang.update',$dataDetailKib->id)}}" method="POST">
    @CSRF
    @method('PUT')
<x-common.component-card title="Form Input Data Kartu Inventaris">
    <!-- Elements -->
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nama Klasifikasi Inventaris
        </label>
        <input type="text" placeholder="Masukkan Karakter " name="kode_kib" value="{{ $dataDetailKib->kode_kib }}" 
            class="h-11 w-full rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed"
            />
            @error('kode_kib')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
    </div>

    <!-- Elements -->
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Klasifikasi Inventaris
        </label>
        <input type="text" placeholder="Masukkan Klafikasi Mengenai KIB" name="klasifikasi" value="{{$dataDetailKib->klasifikasi }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
         @error('klasifikasi')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
    </div>

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Deskripsi Inventaris
        </label>
        <input type="text" placeholder="Masukkan deksripsi Mengenai KIB dan klasifikasinya" name="deskripsi" value="{{ $dataDetailKib->deskripsi }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
         @error('deskripsi')
            <p class="mt-1 text-sm text-error-500">
                {{ $message }}
            </p>
            @enderror
     </div>


   


    <!-- Elements -->
    
    <x-ui.button size="sm" variant="primary" type="submit" >Simpan</x-ui.button>
</div>
</x-common.component-card>
</form>
