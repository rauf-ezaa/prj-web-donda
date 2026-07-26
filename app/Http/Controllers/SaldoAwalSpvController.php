<?php
namespace App\Http\Controllers;

use App\Models\SaldoAwal;
use App\Services\SaldoAwalService;
use Illuminate\Http\Request;

class SaldoAwalSpvController extends Controller
{
    public function __construct(private SaldoAwalService $service) {}

    public function index()
    {
        $saldoAwals = SaldoAwal::with(['dibuatOleh'])
            ->where('status', 'menunggu_verifikasi_spv')
            ->latest()
            ->paginate(10);

        return view('spv.saldo-awal.index', compact('saldoAwals'));
    }

    public function show(SaldoAwal $saldoAwal)
    {
        abort_unless($saldoAwal->status === 'menunggu_verifikasi_spv', 403, 'Saldo awal ini bukan di tahap verifikasi.');

        $saldoAwal->load(['items.barang', 'dibuatOleh']);

        return view('spv.saldo-awal.show', compact('saldoAwal'));
    }

    public function verify(SaldoAwal $saldoAwal)
    {
	 			$this->opnameLock->assertNotLocked();
        try {
            $this->service->verify($saldoAwal, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()->route('spv.saldo-awal.index')
            ->with('success', 'Saldo awal diverifikasi, stok telah diperbarui.');
    }

    public function reject(Request $request, SaldoAwal $saldoAwal)
    {
        $request->validate(['alasan' => 'required|string|max:500']);

        $this->service->reject($saldoAwal, auth()->id(), $request->alasan);

        return redirect()->route('spv.saldo-awal.index')
            ->with('success', 'Saldo awal ditolak.');
    }
}
