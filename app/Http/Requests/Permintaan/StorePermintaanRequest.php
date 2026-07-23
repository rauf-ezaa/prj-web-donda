<?php

namespace App\Http\Requests\Permintaan;

use Illuminate\Foundation\Http\FormRequest;

class StorePermintaanRequest extends FormRequest
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
            'barang_id' => ['required'],
            'jumlah_permintaan' => ['required', 'numeric', 'min:1'],


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
            'barang_id.required' => 'Barang wajib diisi.',
            'jumlah_permintaan.required' => 'Jumlah Permintaan Barang Wajib Diisi',
            'jumlah_permintaan.numeric' => 'Jumlah Permintaan Barang Wajib Angka',
						'jumlah_permintaan.min' => 'Jumlah permintaan barang tidak boleh 0.'
        ];
    }
}
