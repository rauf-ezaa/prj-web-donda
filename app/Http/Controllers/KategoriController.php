<?php

namespace App\Http\Controllers;

use Alert;
use App\Http\Requests\Kategori\StoreKategoriRequest;
use App\Models\Barang;
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

				$title = 'Delete User!';
					$text = "Are you sure you want to delete?";
					confirmDelete($title, $text);

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

        $dataKib = KIB::select(['id', 'kode_kib', 'klasifikasi'])->get();

        $detailKategori = Kategori::where('id',$id)
				->with(['kib'])
				->select([
					'id',
					'nama_kategori',
					'jenis_barang',
					'kode_kib',
				])
				->first();

        $data = [
            'id' => $detailKategori->id,
            'nama_kategori' => $detailKategori->nama_kategori,
            'kode_kib' => $detailKategori->kode_kib,
            'jenis_barang' => $detailKategori->jenis_barang,
            'detail' => $detailKategori->kib?->klasifikasi,
        ];

				// dd($data);
				// dd($dataKib);

        return view('pages.admin.data-kategori.edit-kategori', compact('data','dataKib'), ['title' => 'Edit Data Kategori']);



    }

    /**
     * Update the specified resource in storage.
     */
					public function update(Request $request, $id)
			{
				// dd($request->all());
					$validated = $request->validate([
							'nama_kategori' => 'required|string|max:255',
							'jenis_barang'  => 'required|in:persediaan,aset',
							'kode_kib'        => 'required|exists:kib,id',
					]);

					$kategori = Kategori::findOrFail($id);

					$kategori->update($validated);

					Alert::success('Berhasil', 'Data kategori berhasil diperbarui.');

					return redirect()->route('category.index');
			}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori, $id)

		{
				if ($kategori->barang()->exists()) {
						return back()->with('error', "Kategori \"{$kategori->nama_kategori}\" tidak bisa dihapus karena masih digunakan oleh barang.");
				}

					Alert::success('Berhasil');


				return redirect()
						->route('category.index')
						->with('success', "Kategori \"{$kategori->nama_kategori}\" berhasil dihapus.");
				}
		}
