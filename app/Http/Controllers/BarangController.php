<?php

namespace App\Http\Controllers;

use Alert;
use App\Http\Requests\Barang\StoreBarangRequest;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\KIB;
// use Illuminate\Http\Request;

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

					$title = 'Delete User!';
					$text = "Are you sure you want to delete?";
					confirmDelete($title, $text);

         return view('pages.admin.data-barang.index', compact('barang'), ['title' => 'List Data Barang']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = KIB::get();

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
			  $dataKategori = Kategori::with('kib')
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'nama_kategori' => $item->nama_kategori,
            'kib_id' => $item->kode_kib,
            'kode_kib' => $item->kib?->kode_kib,
            ]);

			// $item = Barang::where('id',$id)
			// ->with(['kib','kategori'])
			// ->select([
			// 	'id',
			// 	'nama_barang',
			// 	'harga_barang',
			// 	'stok_tersedia',
			// 	'klasifikasi_kib',
			// 	'kategori_id',
      //   ])
      //   ->first();

				// dd();

				$detailBarangUpdate =
				[
					'id' => $item->id,
					'nama' => $item->nama_barang,
					'stok' => $item->stok_tersedia,
					'harga' => $item->harga_barang,
					'deskripsi' => $item->kib?->klasifikasi,
					'klasifikasi_kib' => $item->klasifikasi_kib,
					'kategori_id'=> $item->kategori_id,
					'nama_kategori' => $item->kategori?->nama_kategori,
				];


        // ->map(fn ($item) => [
				// 	'id' => $item->id,
				// 	'nama' => $item->nama_barang,
				// 	'stok' => $item->stok_tersedia,
				// 	'harga' => $item->harga_barang,
				// 	'deskripsi' => $item->kib?->klasifikasi,
				// 	'klasifikasi_kib' => $item->kib?->kode_kib
				// 	]);

    // return view('barang.edit', compact(
    //     'barang',
    //     'dataKategori'
    // ));

        // dd($data);
        return view('pages.admin.data-barang.edit-barang',compact('detailBarangUpdate','dataKategori'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBarangRequest $request, Barang $barang, $id)
    {
         try {
					$barangAkanUpdate = Barang::find($id);
					$barangAkanUpdate->update($request->validated());

					 Alert::success('Data Barang Berhasil Diperbarui');
           return redirect()->route('data-barang.index');

        } catch (\Throwable $e) {
            return redirect()
            ->back()
            ->with('error')
            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang, $id)
    {
			$dataBeforedelete = Barang::with('kategori')->find($id);
			// dd($dataBeforedelete);
			Alert::success('info','Data Berhasil Dihapus');
			return redirect()->back();
    }
}
