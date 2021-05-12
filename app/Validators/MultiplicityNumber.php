<?php
declare( strict_types = 1 );

namespace App\Validators;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class MultiplicityNumber
 * @package App\Http\Validators
 */
class MultiplicityNumber implements Rule
{
    /**
     * var int
     */
    public $multiplicity;

    /**
     * var string
     */
    public $message;

    /**
     * @param int $multiplicity
     */
    public function __construct(int $multiplicity)
    {
        $this->multiplicity = $multiplicity;
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
        if (!empty($value) && $value % $this->multiplicity != 0) {
            $$this->message = __('validation', [
                'attribute' => $attribute,
                'multiplicity' => $this->multiplicity,
            ]);
        }

        return empty($this->message) ? true : false;
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
