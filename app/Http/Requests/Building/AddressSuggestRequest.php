<?php

namespace App\Http\Requests\Building;

use Illuminate\Foundation\Http\FormRequest;

class AddressSuggestRequest extends FormRequest
{
    /*
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /*
     * @return array
     */
    public function rules() : array
    {
        return [
            'q'  => [
                'required',
                'string',
                'min:2',
            ],
        ];
    }
}
