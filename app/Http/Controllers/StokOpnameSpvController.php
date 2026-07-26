<?php
namespace App\Http\Controllers;

use App\Models\StokOpname;
use App\Services\StokOpnameService;
use Illuminate\Http\Request;

class StokOpnameSpvController extends Controller
{
    public function __construct(private StokOpnameService $service) {}

    public function index()
    {
        $stokOpnames = StokOpname::with(['periode', 'dibuatOleh'])
            ->where('status', 'menunggu_verifikasi_spv')
            ->latest()
            ->paginate(10);

        return view('spv.stok-opname.index', compact('stokOpnames'));
    }

    public function show(StokOpname $stokOpname)
    {
        abort_unless($stokOpname->status === 'menunggu_verifikasi_spv', 403, 'Opname ini bukan di tahap verifikasi.');
        $stokOpname->load('items.barang', 'periode', 'dibuatOleh');

        return view('spv.stok-opname.show', compact('stokOpname'));
    }

    public function verify(StokOpname $stokOpname)
    {
        try {
            $this->service->verify($stokOpname, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()->route('spv.stok-opname.index')
            ->with('success', 'Stok opname diverifikasi, stok telah disesuaikan.');
    }

    public function cancel(Request $request, StokOpname $stokOpname)
    {
        $request->validate(['catatan_cancel' => 'required|string|max:500']);

        try {
            $this->service->cancel($stokOpname, auth()->id(), $request->catatan_cancel);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()->route('spv.stok-opname.index')
            ->with('success', 'Stok opname dibatalkan, dikembalikan ke admin untuk direvisi.');
    }
}
