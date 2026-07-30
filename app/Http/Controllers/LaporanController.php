<?php
namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(private ReportService $service) {}


		public function statistik()
		{
				$statistik = $this->service->statistikBarang(); // global, semua user
				return view('laporan.statistik', compact('statistik'));
		}


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

public function detailPdf(string $modul, int $id)
{
    $daftarModul = $this->service->daftarModul();
    abort_unless(isset($daftarModul[$modul]), 404);

    ['modul' => $modulInfo, 'row' => $row] = $this->service->detailTransaksi($modul, $id);

    $namaRelasiItems = $modulInfo['nama_relasi_items'];
    $items = $row->{$namaRelasiItems};

    $pdf = Pdf::loadView('laporan.pdf.detail', [
        'modulKey'    => $modul,
        'modulInfo'   => $modulInfo,
        'row'         => $row,
        'items'       => $items,
        'kolomKode'   => $modulInfo['kolom_kode'],
        'service'     => $this->service,
    ])->setPaper('a4', 'portrait');

    $kode = $row->{$modulInfo['kolom_kode']} ?? "#{$row->id}";
    $namaFile = str_replace(['/', ' '], '-', "{$modulInfo['label']}-{$kode}") . '.pdf';

    return $pdf->stream($namaFile);
}

}
