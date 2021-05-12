<?php

namespace App\Http\Requests\Building;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class MassDestroyRequest extends FormRequest
{
    public function authorize()
    {
        return /*abort_if(Gate::denies('building_delete'), 403, '403 Forbidden') ?? */true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:buildings,id',
        ];
    }
}
