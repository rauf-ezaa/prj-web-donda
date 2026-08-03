<?php
namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function __construct(private ReportService $service) {}


		public function dataPengajuan(Request $request)
		{
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $pengajuanQuery = Pengajuan::query();

        if ($startDate && $endDate) {
            $pengajuanQuery->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ]);
        }

        $pengajuanIds = (clone $pengajuanQuery)->pluck('id');

        $summary = [
            'total_pengajuan' => (clone $pengajuanQuery)->count(),

            'total_item_unik' => PengajuanDetail::query()
                ->whereIn('pengajuan_id', $pengajuanIds)
                ->distinct('nama_barang_diajukan')
                ->count('nama_barang_diajukan'),

            'total_qty' => PengajuanDetail::query()
                ->whereIn('pengajuan_id', $pengajuanIds)
                ->sum('jumlah_diajukan'),
        ];

        $topBarang = PengajuanDetail::query()
            ->whereIn('pengajuan_id', $pengajuanIds)
            ->select([
                'nama_barang_diajukan',

                DB::raw('COUNT(*) as frekuensi'),

                DB::raw('SUM(jumlah_diajukan) as total_qty'),

                DB::raw('MAX(created_at) as terakhir_diajukan'),
            ])
            ->groupBy('nama_barang_diajukan')
            ->orderByDesc('frekuensi')
            ->limit(20)
            ->get();

        $barangTeratas = $topBarang->first();

        $riwayat = (clone $pengajuanQuery)
            ->with([
                'requestedBy:id,nama_karyawan'
            ])
            ->withCount('details')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'laporan.pengajuan.index',
            compact(
                'summary',
                'topBarang',
                'barangTeratas',
                'riwayat',
                'startDate',
                'endDate'
            )
        );
    }


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
            'dicetakOleh' => auth()->user()->nama_karyawan,
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

    $statusRow = $row->status ?? $row->status_permintaan ?? $row->status_pemintaan ?? null;
    $statusBolehCetak = $modulInfo['status_boleh_cetak'] ?? [];

    abort_unless(
        in_array($statusRow, $statusBolehCetak),
        403,
        'Transaksi ini belum final (masih dalam proses draft/verifikasi), belum dapat dicetak.'
    );

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
