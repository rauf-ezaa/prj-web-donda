<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Peminjaman, Pengembalian};
use App\Services\PengembalianService;
use Illuminate\Http\Request;
use Alert;

class PengembalianStaffController extends Controller
{
    public function __construct(private PengembalianService $service) {}

    /**
		 * Menu utama: daftar peminjaman milik staff yang masih ada sisa qty untuk dikembalikan.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with('items.barang')
            ->where('requested_by', auth()->id())
            ->whereIn('status', ['dipinjam', 'sebagian_dikembalikan']);

        if ($request->filled('search')) {
            $query->where('kode_peminjaman', 'like', '%' . $request->search . '%');
        }


				$peminjaman = $query->latest()->paginate(10)->withQueryString();

        // hitung sisa qty per peminjaman biar gampang ditampilkan di card/tabel
        $peminjaman->getCollection()->transform(function ($p) {
					$p->total_sisa = $p->items->sum(fn ($i) => $i->sisa_qty);
					return $p;
					});

        return view('staff.pengembalian.index', compact('peminjaman'));
    }

    /**
     * Form input pengembalian untuk peminjaman tertentu.
     */
   public function create(Peminjaman $peminjaman)
{
    // 1. Cek kepemilikan DULUAN, sebelum apapun yang lain
    abort_unless($peminjaman->requested_by === auth()->id(), 403);

    // 2. Baru cek kondisi bisnis
    abort_if($peminjaman->status === 'selesai', 403, 'Peminjaman ini sudah selesai dikembalikan.');

    $adaYangPending = $peminjaman->pengembalians()
        ->whereIn('status', ['menunggu_verifikasi_admin', 'menunggu_verifikasi_spv'])
        ->exists();

    if ($adaYangPending) {
        Alert::error('Gagal', 'Masih ada pengajuan pengembalian yang menunggu verifikasi untuk peminjaman ini.');
        return redirect()->route('pengembalian.index');
    }

    $peminjaman->load('items.barang.kib');
    $itemsBisaDikembalikan = $peminjaman->items->filter(fn ($i) => $i->sisa_qty > 0);

    return view('staff.pengembalian.create', compact('peminjaman', 'itemsBisaDikembalikan'));
}

    public function store(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->requested_by === auth()->id(), 403);

           $validated = $request->validate([
							 'items'                      => 'required|array',
								'items.*.peminjaman_item_id' => 'required|exists:peminjaman_items,id',
								'items.*.qty_baik'           => 'nullable|integer|min:0',
								'items.*.qty_rusak_ringan'   => 'nullable|integer|min:0',
								'items.*.qty_rusak_berat'    => 'nullable|integer|min:0',
								'items.*.qty_hilang'         => 'nullable|integer|min:0',
								'items.*.qty_habis_terpakai' => 'nullable|integer|min:0',
								'catatan'                    => 'nullable|string',
						]);

						// cegah submit kalau SEMUA item qty-nya 0/kosong — gak ada barang yang beneran dikembalikan
						$totalQty = collect($validated['items'])->sum(
									fn ($i) => ($i['qty_baik'] ?? 0) + ($i['qty_rusak_ringan'] ?? 0) + ($i['qty_rusak_berat'] ?? 0)
											+ ($i['qty_hilang'] ?? 0) + ($i['qty_habis_terpakai'] ?? 0)
							);


						if ($totalQty <= 0) {
								return back()
										->withErrors(['items' => 'Isi minimal salah satu jumlah (baik/rusak/hilang) untuk barang yang dikembalikan.'])
										->withInput();
						}

        try {
            $this->service->createReturn(
                $peminjaman,
                $validated['items'],
                auth()->id(),
                $validated['catatan'] ?? null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['items' => $e->getMessage()])->withInput();
        }

        return redirect()
            ->route('pengembalian.index')
            ->with('success', 'Pengembalian berhasil diajukan, menunggu verifikasi admin.');
    }

    /**
     * Riwayat semua pengajuan pengembalian staff ini beserta statusnya.
     */
    public function riwayat(Request $request)
    {
        $query = Pengembalian::with('peminjaman')
            ->where('dikembalikan_oleh', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengembalians = $query->latest()->paginate(10)->withQueryString();

        return view('staff.pengembalian.riwayat', compact('pengembalians'));
    }

    public function riwayatShow(Pengembalian $pengembalians)
    {
        $pengembalians->load(['items.peminjamanItem.barang', 'adminVerifikator', 'spvVerifikator']);

        return view('staff.pengembalian.riwayat', compact('pengembalians'));
    }
}
