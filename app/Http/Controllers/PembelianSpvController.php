<?php
namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Services\PembelianService;
use Illuminate\Http\Request;

class PembelianSpvController extends Controller
{
    public function __construct(
			private PembelianService $service,
			private \App\Services\OpnameLockService $opnameLock
			) {}

    public function index()
    {
        $pembelians = Pembelian::with(['dibuatOleh'])
            ->where('status', 'menunggu_verifikasi_spv')
            ->latest()
            ->paginate(10);

        return view('spv.pembelian.index', compact('pembelians'));
    }

    public function show(Pembelian $pembelian)
    {
        abort_unless($pembelian->status === 'menunggu_verifikasi_spv', 403, 'Pembelian ini bukan di tahap verifikasi.');

        $pembelian->load(['items.barang', 'dibuatOleh']);

        return view('spv.pembelian.show', compact('pembelian'));
    }

    public function verify(Pembelian $pembelian)
    {
			  $this->opnameLock->assertNotLocked();
        try {
            $this->service->verify($pembelian, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()->route('spv.pembelian.index')
            ->with('success', 'Pembelian diverifikasi, stok telah diperbarui.');
    }

    public function reject(Request $request, Pembelian $pembelian)
    {
        $request->validate(['alasan' => 'required|string|max:500']);

        $this->service->reject($pembelian, auth()->id(), $request->alasan);

        return redirect()->route('spv.pembelian.index')
            ->with('success', 'Pembelian ditolak.');
    }
}
