<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKibRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_kib' => [
                'required',
                'string',
                'max:10',
                'unique:kibs,kode_kib',
            ],

            'klasifikasi' => [
                'required',
                'string',
                'max:255',
                'unique:kibs,nama_kib',
            ],
        ];
    }
}