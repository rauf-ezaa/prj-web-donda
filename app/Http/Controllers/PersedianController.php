<?php

namespace App\Http\Controllers;

use App\Http\Requests\Persediaan\StorePersediaanRequest;
use App\Models\Barang;
use App\Models\Persedian;
use Carbon\Carbon;
use Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersedianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
				$dataPersediaan = Persedian::with('barang')
				->get()
				->map(fn ($item) => [
							'id' => $item->id,
							'nama_barang' => $item->barang?->nama_barang,
							'harga_barang' => $item->harga_satuan_unit,
							'jumlah_barang_masuk' => $item->harga_satuan_unit,
							'asal_dana' => $item->asal_dana,
							'harga_total' =>$item->harga_total,
							'qty' => $item->qty,
							'tanggal_masuk' => $item->tanggal_masuk,
            ]);

				// dd($dataPersediaan);

				// $dataIndexPersedian =
				// [
				// 	'nama_barang' => $dataPersediaan->barang?->nama_barang,
				// 	'harga_barang' => $dataPersediaan->harga_satuan_unit,
				// 	'jumlah_barang_masuk' => $dataPersediaan->harga_satuan_unit,
				// 	'asal_dana' => $dataPersediaan->asal_dana,
				// ];

				// dd($dataIndexPersedian);
        return view('pages.admin.persediaan-barang.index-persediaan', compact('dataPersediaan'), ['title' => 'List Data Barang Masuk']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
				$dataBarang = Barang::select('id','nama_barang')->get();
				// dd($dataBarang);
        return view('pages.admin.persediaan-barang.create-persediaan', compact('dataBarang'), ['title' => 'Input Data Barang Masuk']);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersediaanRequest $request)
{
    try {
        DB::beginTransaction();

        $validated = $request->validated();
        $totalHarga = $validated['qty'] * $validated['harga_satuan'];

        Persedian::create([
            'barang_id' => $validated['barang_id'],
            'asal_dana' => $validated['asal_dana'],
            'qty' => $validated['qty'],
            'harga_satuan_unit' => $validated['harga_satuan'],
            'harga_total' => $totalHarga,
            'tanggal_masuk' => now(),
        ]);

        DB::commit();
        Alert::success('Input Barang Berhasil Ditambahkan, menunggu persetujuan SPV');
        return redirect()->route('persediaan.index');

    } catch (\Throwable $th) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal menyimpan data')->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Persedian $persedian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persedian $persedian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Persedian $persedian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persedian $persedian)
    {
        //
    }
}
