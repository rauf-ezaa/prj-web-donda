<?php

namespace App\Http\Controllers;

use Alert;
use App\Http\Requests\Kategori\StoreKategoriRequest;
use App\Models\Kategori;
use App\Models\KIB;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::with(['kib'])
        ->select([
            'id',
            'nama_kategori',
            'jenis_barang',
            'kode_kib',
        ])
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'nama_kategori' => $item->nama_kategori,
            'kode_kib' => $item->kib?->kode_kib,
            'jenis_barang' => $item->jenis_barang,
            'detail' => $item->kib?->klasifikasi,
        ]);

        // dd($kategori);
        
         return view('pages.admin.data-kategori.index-kategori', compact('kategori'), ['title' => 'List Data Kategori']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $dataKategori =  KIB::all();

        return view('pages.admin.data-kategori.create-kategori', compact('dataKategori'), ['title' => 'Create Data Kategori']);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriRequest $request)
    {

         try {
           Kategori::create($request->validated());
           Alert::success('Data Kategori Berhasil Ditambahkan.');
           return redirect()->route('category.index');
           
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
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori, $id)
    {

        // $detailKategori = Kategori::with(['kib'])
        // ->find($id);

        $detailKategori = Kategori::find($id);

        $data = [
            'id' => $detailKategori->id,
            'nama_kategori' => $detailKategori->nama_kategori,
            'kode_kib' => $detailKategori->kode_kib,
            'jenis_barang' => $detailKategori->jenis_barang,
            // 'detail' => $detailKategori->kib?->klasifikasi,
        ];

        return view('pages.admin.data-kategori.edit-kategori', ['title' => 'Create Data Kategori']);
        


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        //
    }
}
