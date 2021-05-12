<?php

namespace App\Http\Requests\Block;

use App\Http\Requests\Base\AjaxRequest;
use App\Models\Block;
use App\Validators\MultiplicityNumber;
use Illuminate\Support\Facades\Gate;

class MassMarketingRequest extends AjaxRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'ids' => [
                'required',
                'array',
            ],
            'ids.*' => [
                'required',
                'distinct',
                'integer',
                'exists:blocks,id',
            ],
            'bet' => [
                'nullable',
                'integer',
                'min:0',
                'max:9990',
                new MultiplicityNumber(10),
            ],
            'cian' => [
                'nullable',
                'integer',
                'in:0,' . implode(',', array_keys(Block::CIAN_PROMOS)),
            ],
            'avito_promo' => [
                'nullable',
                'integer',
                'in:0,' . implode(',', array_keys(Block::AVITO_PROMOS))
            ],
            'yandex_promo' => [
                'nullable',
                'integer',
                'in:0,' . implode(',', array_keys(Block::YANDEX_PROMOS))
            ]
        ];

        return $rules;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return $this->get('ids');
    }


    /**
     * @return int|null
     */
    public function getBet(): ?int
    {
        return $this->get('bet');
    }

    /**
     * @return int|null
     */
    public function getCian(): ?int
    {
        return $this->get('cian');
    }

    /**
     * @return int|null
     */
    public function getAvitoPromo(): ?int
    {
        return $this->get('avito_promo');
    }

    /**
     * @return int|null
     */
    public function getYandexPromo(): ?int
    {
        return $this->get('yandex_promo');
    }
}
