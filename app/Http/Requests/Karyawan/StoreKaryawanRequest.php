<?php

namespace App\Http\Requests\Karyawan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKaryawanRequest extends FormRequest
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
        return [
        'nama_karyawan' => ['required'],

        'nrk' =>
				[
            'required',
            Rule::unique('karyawans', 'nrk')
        ],

        'nip' =>
				[
            'nullable',
            Rule::unique('karyawans', 'nip')
        ],
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
            'nama_karyawan.required' => 'Nama pegawai wajib diisi.',
            'nrk.required' => 'NRK wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nrk.unique' => 'NRK sudah terdaftar.',
        ];
    }
}
