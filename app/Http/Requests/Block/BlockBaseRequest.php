<?php

namespace App\Http\Requests\Block;

use App\Models\Block;
use App\Models\Building;
use App\Validators\MultiplicityNumber;
use Illuminate\Foundation\Http\FormRequest;

class BlockBaseRequest extends FormRequest
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
        $maxFloor = Building::find($this->request->get('building_id'))->floors;

        //maybe it incorrect...but

        $required = 'required';

        if ((int)$this->get('status') === Block::STATUS_DRAFT) {
            $required = 'nullable';
        }

        return [
            'building_id' => ['required', 'integer', 'exists:buildings,id'],
            'floor' => [$required, 'integer', 'lte:' . $maxFloor],
            'flat_number' => ['nullable', 'integer'],
            'area' => [$required, 'numeric', 'gt:0', 'between:0,999.99'],
            'living_area' => ['nullable', 'numeric', 'gt:0', 'between:0,999.99'],
            'kitchen_area' => ['nullable', 'numeric', 'gt:0', 'between:0,99.99'],
            'type' => [$required, 'integer'],
            'rooms' => [$required, 'integer'],
            'rooms_type' => [$required, 'integer'],
            'balcony' => [$required, 'integer'],
            'windowsInOut' => [$required, 'integer'],
            'separate_wc_count' => ['nullable', 'integer'],
            'combined_wc_count' => ['nullable', 'integer'],
            'renovation' => [$required, 'integer'],
            'filling' => ['nullable', 'array'],
            'filling.*' => ['integer', 'in:1,2,3,4,5,6,7,8,9'],
            'shower_bath' => ['nullable', 'array'],
            'shower_bath.*' => ['integer', 'in:1,2'],
            'living_conds' => ['nullable', 'array'],
            'living_conds.*' => ['integer', 'in:1,2,3,4,5'],
            'tenant_count_limit' => ['nullable', 'integer'],
            'cadastral_number' => ['nullable', 'string'],
            'description' => ['nullable', 'string', 'max:4095'],
            'comment' => ['nullable', 'string', 'max:1023'],
            'video_url' => ['nullable', 'string'],
            'status' => [$required, 'integer', 'in:' . implode(',', array_keys(Block::STATUSES))],
            'out_of_market' => [$required],
            'currency' => [$required, 'integer'],
            'contract_signed' => [$required],
            'commission' => [$required, 'numeric', 'gt:0', 'between:0,999.99'],
            'commission_type' => [$required, 'integer'],
            'commission_comment' => ['nullable', 'string', 'max:4095'],
            'included' => ['nullable', 'array'],
            'included.*' => ['integer', 'in:1,2,3'],
            'parking_cost' => ['nullable', 'numeric', 'gt:0', 'between:0,99999.99'],
            'cost' => [$required, 'numeric', 'gt:0', 'between:0,99999999.99'],
            'deposit' => ['nullable', 'numeric', 'gt:0', 'between:0,99999999.99'],
            'bargain' => ['nullable', 'numeric', 'gt:0', 'between:0,99999999.99'],
            'cian' => ['nullable', 'integer', 'in:0,' . implode(',', array_keys(Block::CIAN_PROMOS))],
            'avito_promo' => ['nullable', 'integer', 'in:' . implode(',', array_keys(Block::AVITO_PROMOS))],
            'yandex_promo' => ['nullable', 'integer', 'in:' . implode(',', array_keys(Block::YANDEX_PROMOS))],
            'bet' => [
                'nullable',
                'integer',
                'min:0',
                'max:9990'
            ],//, (new MultiplicityNumber(10))], //todo why it fails create/update test?
            'ad_title' => ['nullable', 'string', 'max:33'],
            'contact' => ['nullable', 'string', 'max:512']
        ];
    }
}
