<?php
namespace App\Http\Controllers;

use App\Services\ReportService;

class RiwayatController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function index()
    {
        $riwayat = $this->service->riwayatUser(auth()->id());

        return view('riwayat.index', compact('riwayat'));
    }

				public function statistikSaya()
		{
				$statistik = $this->service->statistikBarang(auth()->id());
				return view('riwayat.statistik', compact('statistik'));
		}
}
