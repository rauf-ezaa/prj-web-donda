<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Services\PengembalianService;
use Illuminate\Http\Request;

// app/Http/Controllers/Admin/PengembalianVerifikasiController.php
class PengembalianVerifikasiController extends Controller
{
    public function __construct(
			private PengembalianService $service,
			private \App\Services\OpnameLockService $opnameLock
			) {}

    public function index()
    {
        $pengembalians = Pengembalian::with(['peminjaman', 'staff', 'items.peminjamanItem.barang'])
            ->where('status', 'menunggu_verifikasi_spv')
            ->latest()
            ->paginate(10);

        return view('spv.pengembalian.index', compact('pengembalians'));
    }

   public function show(Pengembalian $pengembalian)
{
    abort_unless($pengembalian->status === 'menunggu_verifikasi_spv', 403, 'Pengembalian ini bukan di tahap verifikasi supevisor.');

    $pengembalian->load([
        'peminjaman.requestedBy',
        'staff',
        'items.peminjamanItem.barang',
    ]);

    return view('spv.pengembalian.show', compact('pengembalian'));
}

    public function verify(Pengembalian $pengembalian)
    {
			 $this->opnameLock->assertNotLocked();
        try {
            $this->service->verifyBySpv($pengembalian, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['status' => $e->getMessage()]);
        }

        return redirect()
            ->route('spv.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diverifikasi, stok telah diperbarui.');
    }

    public function reject(Request $request, Pengembalian $pengembalian)
    {
        $request->validate(['alasan' => 'required|string']);

        $this->service->rejectReturn($pengembalian, auth()->id(), $request->alasan);

        return redirect()
            ->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian ditolak.');
    }
}
