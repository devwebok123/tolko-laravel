<?php

namespace App\Http\Requests\Block\Photo;

use App\Http\Requests\Base\AjaxRequest;
use App\Models\Block;
use App\Models\BlockPhoto;

class BulkUpdateRequest extends AjaxRequest
{
    /*
     * @return array
     */
    public function rules(): array
    {
        return [
            'items' => [
                'required',
                'array',
            ],
            'items.*.name' => [
                'nullable',
                'string',
            ],
            'items.*.status' => [
                'required',
                'in:' . implode(',', [BlockPhoto::STATUS_ACTIVE, BlockPhoto::STATUS_NOT_ACTIVE, Block::STATUS_DRAFT]),
            ],
            'items.*.tag_id' => [
                'required',
                'in:' . implode(',', array_keys(BlockPhoto::TAGS)),
            ],
        ];
    }

    /*
     * @return array
     */
    public function attributes(): array
    {
        return [
            'items.*.name' => __('cruds.blockPhoto.fields.name'),
            'items.*.status' => __('cruds.BlockPhoto.fields.status'),
            'items.*.tag_id' => __('cruds.BlockPhoto.fields.tag_id'),
        ];
    }
}
