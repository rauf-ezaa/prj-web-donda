<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataKaryawan = Karyawan::get();
       return view('pages.admin.peminjaman-pengembalian.index-peminjaman', compact('dataKaryawan'), ['title' => 'List Data Peminjaman']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('pages.admin.peminjaman-pengembalian.create-peminjaman', ['title' => 'Create Data Peminjaman']);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         DB::beginTransaction();

    try {

        $barang = Barang::findOrFail(
            $request->barang_id
        );

        /*
        |--------------------------------------------------------------------------
        | Validasi stok
        |--------------------------------------------------------------------------
        */

        if ($barang->stok_tersedia < $request->jumlah) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Stok barang tidak mencukupi.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate nomor peminjaman
        |--------------------------------------------------------------------------
        */

        $nomorPeminjaman =
            'PMJ-' .
            now()->format('YmdHis');

        /*
        |--------------------------------------------------------------------------
        | Simpan peminjaman
        |--------------------------------------------------------------------------
        */

        Peminjaman::create([
            'nomor_peminjaman' => $nomorPeminjaman,
            'barang_id' => $request->barang_id,
            'karyawan_id' => $request->karyawan_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status' => 'dipinjam',
            'keterangan' => $request->keterangan,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kurangi stok
        |--------------------------------------------------------------------------
        */

        $barang->decrement(
            'stok_tersedia',
            $request->jumlah
        );

        DB::commit();

        return redirect()
            ->route('peminjaman.index')
            ->with(
                'success',
                'Peminjaman berhasil dibuat.'
            );

    } catch (\Throwable $e) {

        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'Peminjaman gagal disimpan.'
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
