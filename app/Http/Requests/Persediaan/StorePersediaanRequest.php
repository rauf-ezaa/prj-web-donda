<?php

namespace App\Http\Requests\Persediaan;

use Illuminate\Foundation\Http\FormRequest;

class StorePersediaanRequest extends FormRequest
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
            'asal_dana' => ['required', 'string'],
            'qty' => ['required', 'numeric','min:1'],
            'harga_satuan' => ['required','numeric'],

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
            'barang.required' => 'data barang wajib diisi.',
            'asal_dana.required' => 'Asal Dana wajib diisi.',
            'harga_satuan.required' => 'harga barang per satuan wajib diisi.',
            'harga_satuan.numeric' => 'harga barang wajib diisi angka.',
            'qty.required' => 'quantity wajib diisi.',
            'qty.numeric' => 'quantity wajib diisi angka.',
						'qty.min' => 'quantity tidak boleh dibwawah 1,',
        ];
    }
}
