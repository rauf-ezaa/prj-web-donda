<?php

namespace App\Http\Requests\Kategori;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
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
        // dd($this->route()->parameters());
        return [
        'nama_kategori' => ['required'],
        'jenis_barang' => ['required'],
        'kode_kib' => ['required'],

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
            'nama_kategori.required' => 'Nama Kategori wajib diisi.',
            'jenis_barang.required' => 'Jenis Barang Wajib diisi.',
            'kode_kib.required' => 'Kode KIB wajib diisi.',
        ];
    }
}

