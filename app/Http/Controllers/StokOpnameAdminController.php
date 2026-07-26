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

    public function index()
    {
        $stokOpnames = StokOpname::with(['periode', 'dibuatOleh', 'diverifikasiOleh'])
            ->where('dibuat_oleh', auth()->id())
            ->latest()
            ->paginate(10);

        return view('admin.stok-opname.index', compact('stokOpnames'));
    }

		public function create()
		{
				// hanya periode yang belum punya opname draft/pending milik admin ini
				$periodes = Periode::where('status', 'aktif')
						->whereDoesntHave('stokOpnames', function ($q) {
								$q->where('dibuat_oleh', auth()->id())
									->whereIn('status', ['draft', 'menunggu_verifikasi_spv', 'dibatalkan_spv']);
						})
						->get();

				return view('admin.stok-opname.create', compact('periodes'));
		}

    public function start(Request $request)
    {
			 $this->opnameLock->assertNotLocked();
        $validated = $request->validate(['periode_id' => 'required|exists:periodes,id']);
        $periode = Periode::findOrFail($validated['periode_id']);

        try {
            $stokOpname = $this->service->start($periode, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['opname' => $e->getMessage()]);
        }

        return redirect()->route('admin.stok-opname.edit', $stokOpname->id);
    }

    public function edit(StokOpname $stokOpname)
    {
        abort_unless($stokOpname->dibuat_oleh === auth()->id(), 403);
        abort_unless(in_array($stokOpname->status, ['draft', 'dibatalkan_spv']), 403, 'Opname ini tidak dapat diedit pada status saat ini.');

        $stokOpname->load('items.barang', 'periode');

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
        $stokOpname->load('items.barang', 'periode', 'diverifikasiOleh');

        return view('admin.stok-opname.show', compact('stokOpname'));
    }
}
