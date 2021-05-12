<?php

namespace App\Http\Requests\ApiRosReestr;

use Illuminate\Foundation\Http\FormRequest;

class CadastralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cadastral' => [
                'required',
                'string'
            ],
        ];
    }

    /**
     * @return string
     */
    public function getCadastral(): string
    {
        return $this->get('cadastral');
    }
}
