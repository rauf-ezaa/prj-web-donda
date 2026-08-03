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

		// tambahkan/lengkapi di PembelianAdminController

public function edit(Pembelian $pembelian)
{

    abort_unless($pembelian->dibuat_oleh === auth()->id(), 403);
abort_unless(
    $pembelian->status === 'menunggu_verifikasi_spv',
    403,
    'Pembelian ini tidak dapat diedit pada status saat ini.'
);

$pembelian->load('items.barang');

$dataBarang = Barang::select(
    'id',
    'nama_barang',
    'satuan'
)
->orderBy('nama_barang')
->get();

$existingItems = $pembelian->items
    ->map(function ($item) {
        return [
            'barang_id'    => $item->barang_id,
            'nama_barang'  => $item->barang->nama_barang,
            'satuan'       => $item->barang->satuan,
            'qty'          => $item->qty,
            'deskripsi'    => $item->deskripsi,
        ];
    })
    ->values();

return view(
    'admin.pembelian.edit',
    compact(
        'pembelian',
        'dataBarang',
        'existingItems'
    )
);
}

public function update(Request $request, Pembelian $pembelian)
{

    abort_unless($pembelian->dibuat_oleh === auth()->id(), 403);

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
        $this->service->update(
            $pembelian,
            $validated['items'],
            $validated['nama_supplier'],
            $validated['tanggal_diterima'],
            $validated['catatan'] ?? null
        );
    } catch (\InvalidArgumentException $e) {
        return back()->withErrors(['items' => $e->getMessage()])->withInput();
    }

    return redirect()
        ->route('pembelian.show', $pembelian->id)
        ->with('success', "Pembelian {$pembelian->no_transaksi} berhasil diperbarui.");
}

}
