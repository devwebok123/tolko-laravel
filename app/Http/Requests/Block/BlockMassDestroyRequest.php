<?php

namespace App\Http\Requests\Block;

use Illuminate\Foundation\Http\FormRequest;

class BlockMassDestroyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:blocks,id',
        ];
    }
}
