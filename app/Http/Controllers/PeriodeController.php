<?php
namespace App\Http\Controllers;

use App\Models\Periode;
use App\Services\StokOpnameService;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function __construct(private StokOpnameService $service) {}

    public function index()
    {
        $periodes = Periode::withCount('stokOpnames')->latest()->paginate(10);
        return view('spv.periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('spv.periode.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|in:ganjil,genap',
            'tahun'    => 'required|integer|min:2020|max:2100',
        ]);

        $nama = ucfirst($validated['semester']) . ' ' . $validated['tahun'];

        Periode::create([
            'nama'     => $nama,
            'semester' => $validated['semester'],
            'tahun'    => $validated['tahun'],
            'status'   => 'aktif',
        ]);

        return redirect()->route('spv.periode.index')->with('success', "Periode {$nama} berhasil dibuat.");
    }

    public function kunci(Periode $periode)
    {
        try {
            $this->service->kunciPeriode($periode, auth()->id());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['periode' => $e->getMessage()]);
        }

        return back()->with('success', "Periode {$periode->nama} berhasil dikunci.");
    }

    public function bukaKunci(Periode $periode)
    {
        $this->service->bukaKunciPeriode($periode);
        return back()->with('success', "Periode {$periode->nama} berhasil dibuka kembali.");
    }
}
