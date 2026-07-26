<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\SaldoAwal;
use App\Services\SaldoAwalService;
use Illuminate\Http\Request;

class SaldoAwalAdminController extends Controller
{
    public function __construct(
			private SaldoAwalService $service,
			private \App\Services\OpnameLockService $opnameLock
			) {}

    public function index(Request $request)
    {
        $query = SaldoAwal::with(['dibuatOleh', 'diverifikasiOleh']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $saldoAwals = $query->latest()->paginate(10)->withQueryString();

        return view('admin.saldo-awal.index', compact('saldoAwals'));
    }

   public function create()
{
    $dataBarang = Barang::select('id', 'nama_barang', 'satuan', 'stok_tersedia')
        ->orderBy('nama_barang')
        ->get();

    return view('admin.saldo-awal.create', compact('dataBarang'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'periode_id'          => 'required|exists:periodes,id',
        'stok_opname_id'      => 'nullable|exists:stok_opnames,id',
        'tanggal_pencatatan'  => 'required|date',
        'catatan'             => 'nullable|string',
        'items'               => 'required|array|min:1',
        'items.*.barang_id'   => 'required|exists:barangs,id',
        'items.*.qty'         => 'required|integer|min:1',
    ]);

    try {
        $saldoAwal = $this->service->create(
            $validated['items'],
            $validated['tanggal_pencatatan'],
            auth()->id(),
            $validated['catatan'] ?? null,
            $validated['periode_id'],
            $validated['stok_opname_id'] ?? null
        );
    } catch (\InvalidArgumentException $e) {
        return back()->withErrors(['items' => $e->getMessage()])->withInput();
    }

    return redirect()->route('admin.saldo-awal.index')
        ->with('success', "Saldo awal {$saldoAwal->no_transaksi} berhasil diajukan.");
}

    public function show(SaldoAwal $saldoAwal)
    {
        $saldoAwal->load(['items.barang', 'dibuatOleh', 'diverifikasiOleh']);

        return view('admin.saldo-awal.show', compact('saldoAwal'));
    }

				public function rincian(int $barangId)
		{
				$barang = Barang::findOrFail($barangId);

				$items = \App\Models\SaldoAwalItem::where('barang_id', $barangId)
						->whereHas('saldoAwal', fn ($q) => $q->where('status', 'selesai'))
						->with('saldoAwal.dibuatOleh')
						->get();

				return view('admin.saldo-awal.rincian', compact('barang', 'items'));
		}

		public function rekap()
		{
				$rekap = \App\Models\SaldoAwalItem::query()
						->select('barang_id')
						->selectRaw('SUM(qty) as total_qty')
						->selectRaw('COUNT(DISTINCT saldo_awal_id) as jumlah_sesi')
						->whereHas('saldoAwal', fn ($q) => $q->where('status', 'selesai'))
						->with('barang')
						->groupBy('barang_id')
						->get();

				return view('admin.saldo-awal.rekap', compact('rekap'));
		}

		// tambahkan method baru di SaldoAwalAdminController
public function createFromPeriode(Request $request)
{
    $validated = $request->validate(['periode_id' => 'required|exists:periodes,id']);
    $periode = \App\Models\Periode::findOrFail($validated['periode_id']);

    $periodeSebelumnya = \App\Models\Periode::where('id', '<', $periode->id)->latest('id')->first();

    $opnameTerakhir = null;
    if ($periodeSebelumnya) {
        $opnameTerakhir = app(\App\Services\StokOpnameService::class)
            ->draftSaldoAwalDariOpnameTerakhir($periodeSebelumnya);
    }

    if ($opnameTerakhir) {
        // mode auto-carry: draft diisi dari hasil opname
        $draftItems = $opnameTerakhir->items->map(fn ($item) => [
            'barang_id' => $item->barang_id,
            'nama_barang' => $item->barang->nama_barang,
            'satuan' => $item->barang->satuan,
            'qty' => $item->stok_fisik,
        ]);

        return view('admin.saldo-awal.create', [
            'dataBarang' => \App\Models\Barang::select('id', 'nama_barang', 'satuan')->get(),
            'periode' => $periode,
            'draftItems' => $draftItems,
            'sumberOpname' => $opnameTerakhir,
        ]);
    }

    // mode manual dari nol — belum ada opname sebelumnya sama sekali
    return view('admin.saldo-awal.create', [
        'dataBarang' => \App\Models\Barang::select('id', 'nama_barang', 'satuan')->get(),
        'periode' => $periode,
        'draftItems' => collect(),
        'sumberOpname' => null,
    ]);
}
}
