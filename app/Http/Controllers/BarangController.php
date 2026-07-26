<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KIB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
			public function __construct(
				private \App\Services\OpnameLockService $opnameLock
		) {}

    public function index(Request $request)
    {
        $query = Barang::with('kib');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('klasifikasi_kib')) {
            $query->where('klasifikasi_kib', $request->klasifikasi_kib);
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        // untuk filter dropdown + info jumlah barang per klasifikasi
        $kibList = KIB::withCount('barang')->get();

        return view('barang.index', compact('barangs', 'kibList'));
    }

    public function create()
    {
        $kibList = KIB::all();
        return view('barang.create', compact('kibList'));
    }

    public function store(Request $request)
    {
			 $this->opnameLock->assertNotLocked();
        $validated = $request->validate([
            'nama_barang'      => 'required|string|max:100',
            'merk_spesifikasi' => 'nullable|string|max:255',
            'satuan'           => 'required|in:' . implode(',', Barang::SATUAN_OPTIONS),
            // 'harga_barang'     => 'required|numeric|min:0',
            // 'stok_tersedia'    => 'required|integer|min:0',
            'description'      => 'nullable|string',
            'klasifikasi_kib'  => 'required|exists:kib,id',
        ]);

        Barang::create($validated);

        return redirect()->route('data-barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {

        $kibList = KIB::all();
        return view('barang.edit', compact('barang', 'kibList'));
    }

    public function update(Request $request, Barang $barang)
    {
			 $this->opnameLock->assertNotLocked();
        $validated = $request->validate([
            'nama_barang'      => 'required|string|max:100',
            'merk_spesifikasi' => 'nullable|string|max:255',
            'satuan'           => 'required|in:' . implode(',', Barang::SATUAN_OPTIONS),
            // 'harga_barang'     => 'required|numeric|min:0',
            'description'      => 'nullable|string',
            'klasifikasi_kib'  => 'required|exists:kib,id',
        ]);

        $barang->update($validated);

        return redirect()->route('data-barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
			 $this->opnameLock->assertNotLocked();
        // cegah hapus barang yang masih dipakai di transaksi aktif
        $dipakaiDiPeminjaman = $barang->peminjamanDetail()->exists();
        $dipakaiDiPermintaan = $barang->permintaanDetail()->exists();
				$dipakaidiPersediaan = $barang->persediaan()->exists();
				$pengajuanDetail = $barang->pengajuanDetail()->exists();
				$saldoAwalItem = $barang->saldoAwalItem()->exists();
				$StokOpnameItem = $barang->StokOpnameItem()->exists();


        if ($dipakaiDiPeminjaman || $dipakaiDiPermintaan || $dipakaidiPersediaan || $pengajuanDetail || $saldoAwalItem || $StokOpnameItem) {
            return back()->withErrors(['barang' => 'Barang tidak dapat dihapus karena masih memiliki riwayat transaksi.']);
        }

				else{
					$barang->delete();
				}

        return redirect()->route('data-barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
