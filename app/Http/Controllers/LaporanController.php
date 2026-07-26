<?php
namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function index()
    {
        $ringkasan = $this->service->ringkasanSemuaModul();

        return view('laporan.index', compact('ringkasan'));
    }

   public function modul(Request $request, string $modul)
{
    $daftarModul = $this->service->daftarModul();

    abort_unless(isset($daftarModul[$modul]), 404);

    $filter = $request->only(['status', 'dari_tanggal', 'sampai_tanggal']);
    $data = $this->service->laporanModul($modul, $filter);

    return view('laporan.modul', [
        'modul'       => $modul,
        'modulLabel'  => $daftarModul[$modul]['label'],
        'kolomKode'   => $daftarModul[$modul]['kolom_kode'],
        'data'        => $data,
        'filter'      => $filter,
    ]);
}

public function exportPdf(Request $request, string $modul)
    {
        $daftarModul = $this->service->daftarModul();
        abort_unless(isset($daftarModul[$modul]), 404);

        $filter = $request->only(['status', 'dari_tanggal', 'sampai_tanggal']);

        // PDF butuh SEMUA data tanpa pagination, beda dari tampilan web
        $data = $this->service->laporanModulUntukExport($modul, $filter);

        $pdf = Pdf::loadView('laporan.pdf.modul', [
            'modul'       => $modul,
            'modulLabel'  => $daftarModul[$modul]['label'],
            'kolomKode'   => $daftarModul[$modul]['kolom_kode'],
            'data'        => $data,
            'filter'      => $filter,
            'dicetakOleh' => auth()->user()->name,
            'dicetakPada' => now(),
        ])->setPaper('a4', 'portrait');

        $namaFile = 'laporan-' . $modul . '-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($namaFile);
    }

}
