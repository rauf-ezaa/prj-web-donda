<?php
// app/Http/Requests/Karyawan/StoreKaryawanRequest.php
namespace App\Http\Requests\Karyawan;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_karyawan' => 'required|string|max:255',
            'nrk' => 'required|string|unique:users,email',
            'nip' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'jabatan' => 'required|in:pengguna,tata usaha,supervisor',
            'role' => 'required|exists:roles,name',
        ];
    }
}
