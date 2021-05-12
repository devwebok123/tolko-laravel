<?php

namespace App\Http\Requests\Block\Photo;

use App\Http\Requests\Base\AjaxRequest;

class SortableRequest extends AjaxRequest
{
    /*
     * @return array
     */
    public function rules() : array
    {
        return [
            'ids' => [
                'required',
                'array',
            ],
            'ids.*' => [
                'required',
                'exists:block_photos,id',
            ],
        ];
    }
}
