<?php

namespace App\Http\Controllers;


use Alert;
use App\Http\Requests\Karyawan\StoreKaryawanRequest;
use App\Http\Requests\Karyawan\UpdateKaryawanRequest;
use App\Models\Karyawan;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = Karyawan::query()
        ->select([
            'id',
            'nama_karyawan',
            'nrk',
            'nip'
        ])
        ->get()
        ->map(fn ($item) => [
            'id' => $item->id,
            'nama' => $item->nama_karyawan,
            'nrk' => $item->nrk,
            'nip' => $item->nip,
        ]);

        // dd($data);
        // dd($karyawans);
         return view('pages.admin.data-karyawan.index-karyawan', compact('karyawans'), ['title' => 'List Data Karyawan']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('pages.admin.data-karyawan.create-karyawan', ['title' => 'Create Data Karyawan']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKaryawanRequest $request)
    {

        // dd($request->all());
        try {
           Karyawan::create($request->validated());

           Alert::success('Data Karyawan Berhasil Ditambahkan');
           return redirect()->route('data-karyawan.index');

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
    public function show(Karyawan $karyawan)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan, $id)
    {
        $data = Karyawan::find($id);

         return view('pages.admin.data-karyawan.edit-karyawan', compact('data'), ['title' => 'edit Data Karyawan']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKaryawanRequest $request, Karyawan $karyawan, $id)
    {
        try {
            $dataUpdate = Karyawan::find($id);
             $dataUpdate->update($request->validated());



        Alert::success('Data Karyawan Berhasil Diperbarui');
        return redirect()->route('data-karyawan.index');

        } catch (\Throwable $th) {
             return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'Terjadi kesalahan saat memperbarui data karyawan.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan, $id)
    {
        $a = Karyawan::find($id);

        dd($a);
        // Alert::success('iyaa');
        // return redirect()->back();
    }
}
