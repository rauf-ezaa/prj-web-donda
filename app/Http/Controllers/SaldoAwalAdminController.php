<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Periode;
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
			$dataBarang = Barang::select('id', 'nama_barang', 'satuan')
					->orderBy('nama_barang')
					->get();

			$periodeList = \App\Models\Periode::where('status', 'aktif')->get();

			return view('admin.saldo-awal.create', compact('dataBarang', 'periodeList'));
	}

public function store(Request $request)
{

    $validated = $request->validate([

        'tanggal_pencatatan'  => 'required|date',
        'catatan'             => 'nullable|string',
        'items'               => 'required|array|min:1',
        'items.*.barang_id'   => 'required|exists:barangs,id',
        'items.*.qty'         => 'required|integer|min:1',
    ]);

    // guard tambahan: pastikan periode yang dipilih beneran masih aktif
    // (jaga-jaga kalau antara buka form dan submit, SPV keburu kunci periode itu)
    try {
        $saldoAwal = $this->service->create(
            $validated['items'],
            $validated['tanggal_pencatatan'],
            auth()->id(),
            $validated['catatan'] ?? null,
        );
    } catch (\InvalidArgumentException $e) {
        return back()->withErrors(['items' => $e->getMessage()])->withInput();
    }

    return redirect()->route('saldo-awal.index')
        ->with('success', "Saldo awal {$saldoAwal->no_transaksi} berhasil diajukan.");
}

// tambahkan di SaldoAwalAdminController

public function edit(SaldoAwal $saldoAwal)
{

		abort_unless($saldoAwal->dibuat_oleh === auth()->id(), 403);

		abort_unless(
				$saldoAwal->status === 'menunggu_verifikasi_spv',
				403,
				'Saldo awal ini tidak dapat diedit pada status saat ini.'
		);

		$saldoAwal->load('items.barang');

		$dataBarang = Barang::select(
				'id',
				'nama_barang',
				'satuan'
		)
		->orderBy('nama_barang')
		->get();

		$existingItems = $saldoAwal->items
				->map(function ($item) {
						return [
								'barang_id'   => $item->barang_id,
								'nama_barang' => $item->barang->nama_barang,
								'satuan'      => $item->barang->satuan,
								'qty'         => $item->qty,
						];
				})
				->values();

		return view(
				'admin.saldo-awal.edit',
				compact(
						'saldoAwal',
						'dataBarang',
						'existingItems'
				)
		);

}

		public function update(Request $request, SaldoAwal $saldoAwal)
		{
				abort_unless($saldoAwal->dibuat_oleh === auth()->id(), 403);

				$this->opnameLock->assertNotLocked();

				$validated = $request->validate([
						'tanggal_pencatatan'  => 'required|date',
						'catatan'             => 'nullable|string',
						'items'               => 'required|array|min:1',
						'items.*.barang_id'   => 'required|exists:barangs,id',
						'items.*.qty'         => 'required|integer|min:1',
				]);

				try {
						$this->service->update(
								$saldoAwal,
								$validated['items'],
								$validated['tanggal_pencatatan'],
								$validated['catatan'] ?? null
						);
				} catch (\InvalidArgumentException $e) {
						return back()->withErrors(['items' => $e->getMessage()])->withInput();
				}

				return redirect()
						->route('saldo-awal.show', $saldoAwal->id)
						->with('success', "Saldo awal {$saldoAwal->no_transaksi} berhasil diperbarui.");
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
