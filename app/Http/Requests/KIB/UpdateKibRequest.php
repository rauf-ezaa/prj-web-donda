<?php

namespace App\Http\Requests\KIB;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKibRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'deskripsi' => [
                'required',
                'string',
                'max:255',
            ],

            'klasifikasi' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}