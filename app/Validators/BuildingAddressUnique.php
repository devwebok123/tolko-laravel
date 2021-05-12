<?php
declare( strict_types = 1 );

namespace App\Validators;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Requests\Building\AddressInfoRequest;
use App\Models\Building;

/**
 * Class BuildingAddressUnique
 * @package App\Http\Validators
 */
class BuildingAddressUnique implements Rule
{
    /**
     * @var AddressInfoRequest
     */
    public $request;

    /**
     * var string
     */
    public $message;

    /**
     * @param AddressInfoRequest $request
     */
    public function __construct(AddressInfoRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!empty($this->request->q)) {
            $query = Building::where('address', $this->request->q);
            if (!empty($this->request->building_id)) {
                $query->where('id', '!=', $this->request->building_id);
            }

            if ($building = $query->first()) {
                $this->message = __('validation.address_duplicate', [
                    'url' => route('admin.buildings.edit', $building->id),
                ]);
            }
        }

        return empty($this->message);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
