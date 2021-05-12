<?php

namespace App\Http\Requests\Building;

use App\Models\Building;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
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
            'address' => ['string'],
            'name' => ['string', 'nullable'],
            'region_id' => ['integer', 'exists:regions,id'],
            'metro_id' => ['integer', 'exists:metros,id', 'nullable'],
            'metro_id_2' => ['integer', 'exists:metros,id', 'nullable'],
            'metro_id_3' => ['integer', 'exists:metros,id', 'nullable'],
            'metro_time' => ['integer', 'nullable'],
            'metro_time_type' => ['integer', 'nullable'],
            'metro_distance' => ['integer', 'nullable'],
            'mkad_distance' => ['integer', 'nullable'],
            'year_construction' => ['integer', 'nullable'],
            'type' => ['integer', 'nullable', 'in:' . implode(',', array_keys(Building::TYPES))],
            'series' => ['string', 'nullable'],
            'ceil_height' => ['numeric', 'gt:0', 'between:0,99.99', 'nullable'],
            'passenger_lift_count' => ['integer', 'nullable'],
            'cargo_lift_count' => ['integer', 'nullable'],
            'garbage_chute' => ['boolean', 'nullable'],
            'class' => ['in:A,B,C,D', 'nullable'],
            'floors' => ['integer'],
            'parking_type' => ['integer', 'nullable'],
            'near_infra' => ['boolean', 'nullable'],
            'lat' => ['numeric', 'between:-99.99999999,99.99999999', 'nullable'],
            'lng' => ['numeric', 'between:-999.99999999,999.99999999', 'nullable'],
        ];
    }

    public function attributes() : array
    {
        return __('cruds.building.fields');
    }
}
