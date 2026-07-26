<?php

namespace App\Http\Controllers;


use Alert;
use App\Http\Requests\Karyawan\StoreKaryawanRequest;
use App\Http\Requests\Karyawan\UpdateKaryawanRequest;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $karyawans = Karyawan::with('user.roles')->latest()->paginate(10);

        // dd($data);
        // dd($karyawans);
         return view('pages.admin.data-karyawan.index-karyawan', compact('karyawans'), ['title' => 'List Data Karyawan']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $roles = Role::all();

        return view('pages.admin.data-karyawan.create-karyawan', compact('roles'), ['title' => 'Create Data Karyawan']);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreKaryawanRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'nama_karyawan' => $request->nama_karyawan,
                'email' => $request->nrk,
                'password' => Hash::make($request->password),
            ]);

            $user->karyawan()->create([
                'nama_karyawan' => $request->nama_karyawan,
                'nrk' => $request->nrk,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,
            ]);

            $user->syncRoles([$request->role]);

            DB::commit();

            Alert::success('Data Pengguna Berhasil Ditambahkan');
            return redirect()->route('data-pengguna.index');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan data Pengguna.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Karyawan $karyawan)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan)
    {
        $roles = Role::all();
        $userRole = $karyawan->user?->roles->first()?->name;


        return view('pages.admin.data-karyawan.edit-karyawan', compact('karyawan', 'roles', 'userRole'), ['title' => 'Edit Data Karyawan']);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Karyawan $karyawan)
    {
			// dd($request->all());
        DB::beginTransaction();
        try {
            $karyawan->update([
                'nama_karyawan' => $request->nama_karyawan,
                'nrk' => $request->nrk,
                'nip' => $request->nip,
                'jabatan' => $request->jabatan,

											]);


            $karyawan->user()->update([
                'nama_karyawan' => $request->nama_karyawan,
                'email' => $request->nrk,
            ]);

            $karyawan->user->syncRoles([$request->role]);
            DB::commit();


            Alert::success('Data Pengguna Berhasil Diperbarui');
            return redirect()->route('data-pengguna.index');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data Pengguna.');
        }
    }

    public function destroy(User $user, $id)
    {
        $relasiTerpakai = $user->permintaan()->exists()
            || $user->pengajuan()->exists()
            || $user->peminjaman()->exists();

				if(Auth::user()->id == $id){
            return back()->with('error', "Lu mau hapus diri lu sendiri hah?");
				}

        if ($relasiTerpakai) {
            return back()->with('error', "Pengguna \"{$user->nama_karyawan}\" tidak bisa dihapus karena memiliki histori pengajuan/permintaan/peminjaman.");
        }

				if(!$relasiTerpakai){
            return back()->with('success',"Berhasil Terhapus");

					// $user->karyawan()->delete();
					// $user->delete();
				}


        Alert::success('Data Pengguna Berhasil Dihapus');
        return redirect()->route('data-pengguna.index');
    }
}
