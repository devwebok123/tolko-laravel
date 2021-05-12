<?php

namespace App\Http\Requests\Building;

use Illuminate\Foundation\Http\FormRequest;
use App\Validators\BuildingAddressUnique;

class AddressInfoRequest extends FormRequest
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
            'building_id' => [
                'nullable',
                'integer',
                'exists:buildings,id',
            ],
            'q'  => [
                'required',
                'string',
                'min:2',
                new BuildingAddressUnique($this),
            ],
        ];
    }
}
