<?php
namespace App\Http\Controllers;

use App\Models\{Periode, StokOpname};
use App\Services\StokOpnameService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StokOpnameAdminController extends Controller
{
    public function __construct(
			private StokOpnameService $service,
			private \App\Services\OpnameLockService $opnameLock
			) {}


		public function cetakBast(StokOpname $stokOpname)
{
    abort_unless($stokOpname->status === 'selesai', 403, 'BAST hanya dapat dicetak untuk opname yang sudah selesai diverifikasi.');

    $stokOpname->load(['items.barang', 'periode', 'dibuatOleh', 'diverifikasiOleh']);

    $pdf = Pdf::loadView('admin.stok-opname.pdf.bast', [
        'stokOpname' => $stokOpname,
    ])->setPaper('a4', 'portrait');

    $namaFile = 'BAST-' . str_replace(['/', ' '], '-', $stokOpname->no_bast) . '.pdf';

    return $pdf->stream($namaFile);
    // pakai stream() bukan download() supaya bisa langsung dipreview di tab baru,
    // user bisa pilih print/save sendiri dari situ. Ganti ke download() kalau maunya langsung kedownload.
}

public function index(Request $request)
{
		$isDraftOrTransactionExist = StokOpname::where('status',
					'draft'
				)->latest()
        ->first();

    $query = StokOpname::with(['dibuatOleh', 'diverifikasiOleh'])
        ->where('dibuat_oleh', auth()->id());

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama_bulan', 'like', "%{$search}%")
              ->orWhere('no_bast', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $stokOpnames = $query->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.stok-opname.index', compact('stokOpnames','isDraftOrTransactionExist'));
}

		public function create()
		{

				// hanya periode yang belum punya opname draft/pending milik admin ini
			 	$tanggalMinimal = app(\App\Services\StokOpnameService::class)->tanggalSaldoAwalPertama();

    		return view('admin.stok-opname.create', compact('tanggalMinimal'));
		}

    public function start(Request $request)
    {
			 $validated = $request->validate([
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer|min:2020|max:2100',
    ]);

    try {
        $stokOpname = $this->service->start($validated['bulan'], $validated['tahun'], auth()->id());
    } catch (\InvalidArgumentException $e) {
        return back()->withErrors(['opname' => $e->getMessage()]);
    }
        return redirect()->route('admin.stok-opname.edit', $stokOpname->id);
    }

    public function edit(StokOpname $stokOpname)
    {
        abort_unless($stokOpname->dibuat_oleh === auth()->id(), 403);
        abort_unless(in_array($stokOpname->status, ['draft', 'dibatalkan_spv']), 403, 'Opname ini tidak dapat diedit pada status saat ini.');

        $stokOpname->load('items.barang');

        return view('admin.stok-opname.edit', compact('stokOpname'));
    }

    public function submit(Request $request, StokOpname $stokOpname)
    {
        abort_unless($stokOpname->dibuat_oleh === auth()->id(), 403);

        $validated = $request->validate([
            'no_bast'                  => 'required|string|max:100',
            'tanggal_bast'              => 'required|date',
            'catatan'                  => 'nullable|string',
            'items'                    => 'required|array',
            'items.*.item_id'          => 'required|exists:stok_opname_items,id',
            'items.*.stok_fisik'       => 'required|integer|min:0',
            'items.*.keterangan'       => 'nullable|string|max:500',
        ]);

        try {
            $this->service->submit(
                $stokOpname,
                $validated['items'],
                $validated['no_bast'],
                $validated['tanggal_bast'],
                $validated['catatan'] ?? null,
                auth()->id()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['items' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('admin.stok-opname.index')
            ->with('success', 'Stok opname berhasil diajukan, menunggu verifikasi supervisor.');
    }

    public function show(StokOpname $stokOpname)
    {
        abort_unless($stokOpname->dibuat_oleh === auth()->id(), 403);
        $stokOpname->load('items.barang', 'diverifikasiOleh');

        return view('admin.stok-opname.show', compact('stokOpname'));
    }
}
