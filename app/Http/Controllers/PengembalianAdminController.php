<?php
namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Services\PengembalianService;
use Illuminate\Http\Request;

class PengembalianAdminController extends Controller
{
    public function __construct(private PengembalianService $service) {}

    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman.requestedBy', 'staff'])
            ->where('status', 'menunggu_verifikasi_admin')
            ->latest()
            ->paginate(10);

        return view('admin.pengembalian.index', compact('pengembalians'));
    }

    public function show(Pengembalian $pengembalian)
    {
        abort_unless($pengembalian->status === 'menunggu_verifikasi_admin', 403, 'Pengembalian ini bukan di tahap verifikasi admin.');

        $pengembalian->load([
            'peminjaman.requestedBy',
            'staff',
            'items.peminjamanItem.barang.kib',
        ]);

        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    public function verify(Pengembalian $pengembalian)
    {
			 $this->opnameLock->assertNotLocked();
        try {
            $this->service->verifyByAdmin($pengembalian, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian diteruskan ke supervisor untuk verifikasi final.');
    }

    public function reject(Request $request, Pengembalian $pengembalian)
    {
        $request->validate(['alasan' => 'required|string|max:500']);

        $this->service->rejectByAdmin($pengembalian, auth()->id(), $request->alasan);

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian ditolak.');
    }
}
