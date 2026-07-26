@extends('layouts.app')

@section('content')
<div class="p-4 md:p-6 max-w-3xl mx-auto">
    <a href="{{ route('pengembalian.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
        ← Kembali ke Daftar Pengembalian
    </a>

    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90 mb-1">
        Input Pengembalian — {{ $peminjaman->kode_peminjaman }}
    </h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
        Isi jumlah barang yang dikembalikan sesuai kondisinya. Barang rusak tetap dihitung masuk stok, barang hilang atau habis terpakai tidak.
    </p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600 dark:bg-red-500/10 dark:text-red-400">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('pengembalian.store', $peminjaman->id) }}">
        @csrf

        <div class="space-y-4">
            @foreach($itemsBisaDikembalikan as $item)
    @php $isKibB = $item->barang->kib->kode_kib === 'KIB-B'; @endphp

    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
        <input type="hidden" name="items[{{ $loop->index }}][peminjaman_item_id]" value="{{ $item->id }}">

        <div class="mb-3 flex items-center justify-between">
            <p class="font-medium text-gray-800 dark:text-white/90">{{ $item->barang->nama_barang }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Sisa belum kembali: <span class="font-semibold">{{ $item->sisa_qty }}</span> / {{ $item->qty_pinjam }}
            </p>
        </div>

        <div class="grid {{ $isKibB ? 'grid-cols-4' : 'grid-cols-5' }} gap-3">
            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Baik</label>
                <input type="number" min="0" max="{{ $item->sisa_qty }}" value="0"
                    name="items[{{ $loop->index }}][qty_baik]"
                    class="qty-input h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white/90"
                    data-max="{{ $item->sisa_qty }}" data-group="{{ $loop->index }}">
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Rusak Ringan</label>
                <input type="number" min="0" max="{{ $item->sisa_qty }}" value="0"
                    name="items[{{ $loop->index }}][qty_rusak_ringan]"
                    class="qty-input h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white/90"
                    data-max="{{ $item->sisa_qty }}" data-group="{{ $loop->index }}">
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Rusak Berat</label>
                <input type="number" min="0" max="{{ $item->sisa_qty }}" value="0"
                    name="items[{{ $loop->index }}][qty_rusak_berat]"
                    class="qty-input h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white/90"
                    data-max="{{ $item->sisa_qty }}" data-group="{{ $loop->index }}">
            </div>
            <div>
                <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Hilang</label>
                <input type="number" min="0" max="{{ $item->sisa_qty }}" value="0"
                    name="items[{{ $loop->index }}][qty_hilang]"
                    class="qty-input h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white/90"
                    data-max="{{ $item->sisa_qty }}" data-group="{{ $loop->index }}">
            </div>

            @unless($isKibB)
                <div>
                    <label class="mb-1 block text-xs text-gray-500 dark:text-gray-400">Habis Terpakai</label>
                    <input type="number" min="0" max="{{ $item->sisa_qty }}" value="0"
                        name="items[{{ $loop->index }}][qty_habis_terpakai]"
                        class="qty-input h-9 w-full rounded-lg border border-gray-300 bg-transparent px-3 text-sm dark:border-gray-700 dark:text-white/90"
                        data-max="{{ $item->sisa_qty }}" data-group="{{ $loop->index }}">
                </div>
            @endunless
        </div>

        <p class="mt-1 text-xs text-error-500 hidden" id="warning-{{ $loop->index }}">
            Total melebihi sisa qty yang bisa dikembalikan.
        </p>
    </div>
@endforeach
        </div>

        <div class="mt-4">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan (opsional)</label>
            <textarea name="catatan" rows="2"
                class="w-full rounded-lg border border-gray-300 bg-transparent p-3 text-sm dark:border-gray-700 dark:text-white/90"></textarea>
        </div>

        <div class="mt-6 flex gap-2">
            <x-ui.button size="md" variant="primary" type="submit">Ajukan Pengembalian</x-ui.button>
            <a href="{{ route('pengembalian.index') }}">
                <x-ui.button size="md" variant="secondary" type="button">Batal</x-ui.button>
            </a>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', () => {
            const group = input.dataset.group;
            const max = parseInt(input.dataset.max);
            const inputs = document.querySelectorAll(`.qty-input[data-group="${group}"]`);
            const total = Array.from(inputs).reduce((sum, i) => sum + (parseInt(i.value) || 0), 0);
            const warning = document.getElementById(`warning-${group}`);
            warning.classList.toggle('hidden', total <= max);
        });
    });
</script>
@endsection
