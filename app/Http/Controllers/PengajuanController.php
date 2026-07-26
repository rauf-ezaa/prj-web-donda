<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanDetail;
use App\Models\Barang;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
   public function index(Request $request)
{

		$draftAktif = Pengajuan::where('requested_by', auth()->id())
        ->where('status', 'draft')
        ->latest()
        ->first();

    if (!$request->filled('sort')) {
        return view('pages.admin.pengajuan-barang.index-pengajuan', [
            'pengajuan' => null,
            'draftAktif' => $draftAktif,
        ]);
    }

    // Kalau belum pilih sort, jangan query apa-apa
    if (!$request->filled('sort')) {
        return view('pages.admin.pengajuan-barang.index-pengajuan',compact('draftAktif'), ['pengajuan' => null]);
    }


    $query = Pengajuan::with('requestedBy')
        ->where('requested_by', auth()->id());

    match ($request->sort) {
        'draft'     => $query->where('status', 'draft'),
        'pending'  => $query->where('status', 'pending'),
        'approved' => $query->where('status', 'approved'),
        'rejected'   => $query->where('status', 'rejected'),
    };

    $pengajuan = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('pages.admin.pengajuan-barang.index-pengajuan', compact('pengajuan','draftAktif'));
    }

    public function startDraft()
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => $this->generateKodePengajuan(),
            'requested_by' => auth()->id(),
            'alasan_pengajuan' => '',
            'status' => 'draft',
        ]);

        return redirect()->route('pengajuan.draft', $pengajuan->id);
    }

    public function showDraft(Pengajuan $pengajuan)
{
    abort_unless($pengajuan->status === 'draft' || $pengajuan->status === 'pending' && $pengajuan->requested_by == auth()->id(), 403);
    $pengajuan->load('details');

    return view('pages.admin.pengajuan-barang.draft', compact('pengajuan')); // dataBarang dihapus
}


    public function addItem(Pengajuan $pengajuan, Request $request)
{
    abort_unless($pengajuan->status === 'draft' && $pengajuan->requested_by == auth()->id(), 403);

    $validated = $request->validate([
        'nama_barang_diajukan' => 'required|string|max:255',
        'jumlah_diajukan' => 'required|integer|min:1',
    ]);

    $detail = $pengajuan->details()->create([
        'nama_barang_diajukan' => $validated['nama_barang_diajukan'],
        'jumlah_diajukan' => $validated['jumlah_diajukan'],
    ]);

    return response()->json([
        'success' => true,
        'detail' => [
            'id' => $detail->id,
            'nama_barang_diajukan' => $detail->nama_barang_diajukan,
            'jumlah_diajukan' => $detail->jumlah_diajukan,
          ],
    ]);
}

    public function removeItem(Pengajuan $pengajuan, PengajuanDetail $detail)
    {
        abort_unless($pengajuan->status === 'draft' && $pengajuan->requested_by == auth()->id(), 403);
        abort_unless($detail->pengajuan_id == $pengajuan->id, 404);

        $detail->delete();

        return response()->json(['success' => true]);
    }

    public function verifikasi(Pengajuan $pengajuan, Request $request)
    {
        abort_unless($pengajuan->status === 'draft' || $pengajuan->status === 'pending' && $pengajuan->requested_by == auth()->id(), 403);

        if ($pengajuan->details()->count() === 0) {
            return response()->json(['success' => false, 'message' => 'Belum ada barang yang diajukan'], 422);
        }

        $validated = $request->validate([
            'alasan_pengajuan' => 'required|string|max:500',
        ]);

        $pengajuan->update([
            'alasan_pengajuan' => $validated['alasan_pengajuan'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('pengajuan.show', $pengajuan->id),
        ]);
    }

    public function show(Pengajuan $pengajuan)
    {
        abort_unless($pengajuan->requested_by == auth()->id() || auth()->user()->hasAnyRole(['admin', 'spv']), 403);

        $pengajuan->load('details.barang', 'requestedBy', 'approvedBy');

        return view('pages.admin.pengajuan-barang.show', compact('pengajuan'));
    }

		public function batalkan(Pengajuan $pengajuan)
		{
				abort_unless($pengajuan->is_editable && $pengajuan->requested_by == auth()->id(), 403);
				$pengajuan->update(['status' => 'dibatalkan']);

				return redirect()
						->route('pengajuan.index')
						->with('success', 'Pengajuan dibatalkan');
		}



    protected function generateKodePengajuan(): string
    {
        $prefix = 'PGJ-' . now()->format('Ymd') . '-';
        $lastNumber = Pengajuan::where('kode_pengajuan', 'like', $prefix . '%')->count();

        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }


}
