<?php

namespace App\Http\Requests\Cadastral;

use Illuminate\Foundation\Http\FormRequest;

class CadastralCheckRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cadastral_number'   => [
                'nullable',
                'string',
            ],
            'flat_number'   => [
                'nullable',
                'string',
            ],
            'building_address'   => [
                'nullable',
                'string',
            ],
        ];
    }
}
