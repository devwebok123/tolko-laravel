<?php

namespace App\Http\Requests\Base;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AjaxRequest extends FormRequest
{
    /**
     * Get the failed validation response for the request.
     *
     * @param Validator $validator
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator) : HttpResponseException
    {
        throw new HttpResponseException(
            response()->json([
                'message' => __('cruds.the_given_data_was_invalid'),
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
