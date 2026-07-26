<?php

namespace App\Http\Requests\Barang;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
			// dd($this->all());
        return [
            'nama_barang' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:100'],
            'harga_barang' => ['required', 'numeric','decimal:0,2','min:0'],
            'klasifikasi_kib' => ['required'],
            'kategori_id' => ['required'],

        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'deskripsi.required' => 'Deskripsi Barang wajib diisi.',
            'nama_barang.required' => 'Nama Barang wajib diisi.',
            'harga_barang.required' => 'harga_barang wajib diisi.',
            'harga_barang.numeric' => 'wajib diisi angka.',
            'kategori.required' => 'Kategori wajib diisi.',
            'klasifikasi.required' => 'Klasifikasi wajib diisi.',
        ];
    }
}
