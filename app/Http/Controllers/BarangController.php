<?php

namespace App\Http\Controllers;

use Alert;
use App\Http\Requests\Barang\StoreBarangRequest;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\KIB;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with(['kib'])
        ->select([
            'id',
            'nama_barang',
            'harga_barang',
            'stok_tersedia',
            'klasifikasi_kib',
        ])
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'nama' => $item->nama_barang,
            'stok' => $item->stok_tersedia,
            'harga' => $item->harga_barang,
            'deskripsi' => $item->kib?->klasifikasi,
            'klasifikasi_kib' => $item->kib?->kode_kib
        ]);

        // dd($barang);
         return view('pages.admin.data-barang.index', compact('barang'), ['title' => 'List Data Barang']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $dataKategori = Kategori::with('kib')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'nama_kategori' => $item->nama_kategori,
            'kib_id' => $item->kode_kib,
            'kode_kib' => $item->kib?->kode_kib,
            ]);

        //  dd($dataKategori);

        return view('pages.admin.data-barang.create-barang',compact('data','dataKategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBarangRequest $request)
    {

        // dd($request->all());

         try {
           Barang::create($request->validated());

           Alert::success('Data Barang Berhasil Ditambahkan.');
           return redirect()->route('data-barang.index');

        } catch (\Throwable $e) {
            return redirect()
            ->back()
            ->with('error','ayooo')
            ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang, $id)
    {
        $detailBarangUpdate = Barang::find($id);

        // dd($detailBarangUpdate);
        return view('pages.admin.data-barang.edit-barang',compact('detailBarangUpdate'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang, $id)
    {
        $deleteBarang = Barang::find($id);
        dd($deleteBarang);
    }
}
