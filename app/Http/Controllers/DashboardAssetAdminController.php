<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KIB;
use Illuminate\Http\Request;

class DashboardAssetAdminController extends Controller
{
    public function getHalamanSarpras(Request $request){

			 $query = Barang::with('kib')->where('klasifikasi_kib',1);

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('klasifikasi_kib')) {
            $query->where('klasifikasi_kib', $request->klasifikasi_kib);
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        // untuk filter dropdown + info jumlah barang per klasifikasi
        $kibList = KIB::withCount('barang')->get();

        return view('aset-lancar.sarpras.index', compact('barangs', 'kibList'));
		}

		 public function getHalamanAtk(Request $request){

			 $query = Barang::with('kib')->where('klasifikasi_kib',3);

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('klasifikasi_kib')) {
            $query->where('klasifikasi_kib', $request->klasifikasi_kib);
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        // untuk filter dropdown + info jumlah barang per klasifikasi
        $kibList = KIB::withCount('barang')->get();

        return view('aset-lancar.atk.index', compact('barangs', 'kibList'));
		}

		 public function getHalamanKIB(Request $request){

			 $query = Barang::with('kib')->where('klasifikasi_kib',2);

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('klasifikasi_kib')) {
            $query->where('klasifikasi_kib', $request->klasifikasi_kib);
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        // untuk filter dropdown + info jumlah barang per klasifikasi
        $kibList = KIB::withCount('barang')->get();

        return view('aset-tetap.index', compact('barangs', 'kibList'));
		}
}
