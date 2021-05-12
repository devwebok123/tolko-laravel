<?php

namespace App\Http\Requests\Block\Photo;

use App\Http\Requests\Base\AjaxRequest;
use Illuminate\Http\UploadedFile;

class UploadRequest extends AjaxRequest
{
    /*
     * @return array
     */
    public function rules() : array
    {
        return [
            'image_file' => [
                'required',
                'mimes:jpeg,jpg,png',
                'max:' . (1024 * 20), // 20MB
            ],
        ];
    }
    
    /*
     * @return array
     */
    public function attributes() : array
    {
        return [
            'image_file' =>  ($this->image_file instanceof UploadedFile) ?
                $this->image_file->getClientOriginalName() :
                __('cruds.blockPhoto.fields.image_file'),
        ];
    }
    
    /*
     * @return array
     */
    public function messages() : array
    {
        return [
            'image_file.max' => __('validation.upload_max_file_size', [
                'size' => '20',
            ]),
        ];
    }
}
