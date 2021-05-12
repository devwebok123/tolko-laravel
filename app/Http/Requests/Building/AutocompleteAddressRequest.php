<?php


namespace App\Http\Requests\Building;

use App\Services\Models\BuildingService;
use Illuminate\Foundation\Http\FormRequest;

class AutocompleteAddressRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'source' => [
                'nullable',
                'string',
                'in:' . BuildingService::SOURCE_BLOCK,
            ],
            'buildings_address' => [
                'required',
                'string',
                'min:3'
            ]
        ];
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->get('buildings_address');
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->get('source');
    }
}
