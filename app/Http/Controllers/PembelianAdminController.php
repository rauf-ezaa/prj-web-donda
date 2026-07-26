<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Services\PembelianService;
use Illuminate\Http\Request;

class PembelianAdminController extends Controller
{
    public function __construct(
			private PembelianService $service,
			private \App\Services\OpnameLockService $opnameLock
		) {}

    public function index(Request $request)
    {
        $query = Pembelian::with(['dibuatOleh', 'diverifikasiOleh']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembelians = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $dataBarang = Barang::select('id', 'nama_barang', 'satuan')->orderBy('nama_barang')->get();

        return view('admin.pembelian.create', compact('dataBarang'));
    }

    public function store(Request $request)
    {
			   $this->opnameLock->assertNotLocked();
        $validated = $request->validate([
            'nama_supplier'          => 'required|string|max:255',
            'tanggal_diterima'       => 'required|date',
            'catatan'                => 'nullable|string',
            'items'                  => 'required|array|min:1',
            'items.*.barang_id'      => 'required|exists:barangs,id',
            'items.*.qty'            => 'required|integer|min:1',
            'items.*.deskripsi'      => 'nullable|string|max:500',
        ]);

        try {
            $pembelian = $this->service->createPembelian(
                $validated['items'],
                $validated['nama_supplier'],
                $validated['tanggal_diterima'],
                auth()->id(),
                $validated['catatan'] ?? null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['items' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('pembelian.index')
            ->with('success', "Pembelian {$pembelian->no_transaksi} berhasil diajukan, menunggu verifikasi supervisor.");
    }

    public function show(Pembelian $pembelian)
    {
        abort_unless($pembelian->dibuat_oleh === auth()->id() || auth()->user()->hasRole('admin'), 403);

        $pembelian->load(['items.barang', 'dibuatOleh', 'diverifikasiOleh']);

        return view('admin.pembelian.show', compact('pembelian'));
    }
}
