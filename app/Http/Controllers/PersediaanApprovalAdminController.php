<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersediaanApprovalAdminController extends Controller
{
    public function index(){
			$persediaan = Persedian::with('barang')
            ->where('approval_status', 'menunggu')
            ->latest()
            ->get();

        $riwayat = Persedian::with('barang')
            ->whereIn('approval_status', ['diterima', 'ditolak'])
            ->latest()
            ->take(10)
            ->get();


		}
}
