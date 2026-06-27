<?php

namespace App\Http\Controllers;

use Alert;
use App\Http\Requests\StoreKibRequest;
use App\Http\Requests\KIB\UpdateKibRequest;
use App\Models\KIB;
use Illuminate\Http\Request;

class KIBController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $dataKib = KIB::query()
        ->select([
            'id',
            'kode_kib',
            'klasifikasi',
            'deskripsi'
        ])
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'kode_kib' => $item->kode_kib,
            'klasifikasi' => $item->klasifikasi,
            'deskripsi' => $item->deskripsi,
        ]);
        return view('pages.admin.kartu-inventaris-barang.index-inventaris', compact('dataKib'), ['title' => 'List Data KIB']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('pages.admin.kartu-inventaris-barang.create-inventaris');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKibRequest $request)
    {
        KIB::create($request->validated());

        return response()->json('berhasil simpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(KIB $kib, String $klasifikasi)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KIB $kib, String $kode)
    {
        $dataDetailKib = KIB::find($kode);
         
        return view ('pages.admin.kartu-inventaris-barang.edit-inventaris',compact('dataDetailKib'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKibRequest $request, KIB $kib, $id)
    {

        
        try {
            $dataUpdateKIb = KIB::find($id);
            $data = $request->except('kode_kib');
            // dd($data);
            $dataUpdateKIb->update($data);

        Alert::success('Data Kartu Inventaris Barang Berhail Diperbarui');

        return redirect()->route('kartu-inventaris-barang.index');

        } catch (\Throwable $th) {
             return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'Terjadi kesalahan saat memperbarui data kartu inventaris barang.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KIB $kIB)
    {
        //
    }
}
