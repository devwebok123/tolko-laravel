<?php


namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    protected const PHONE_PATTERN = '/[+][0-9]{11}$/';
    protected const PHONE_INVALID_MESSAGE = 'Номер телефона должен быть в формате: +74951122233';

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
    public function rules(): array
    {
        return [
            'phone_cian' => 'required|string|regex:' . self::PHONE_PATTERN,
            'phone_avito' => 'required|string|regex:' . self::PHONE_PATTERN,
            'phone_yandex' => 'required|string|regex:' . self::PHONE_PATTERN,
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'phone_cian.regex' => self::PHONE_INVALID_MESSAGE,
            'phone_avito.regex' => self::PHONE_INVALID_MESSAGE,
            'phone_yandex.regex' => self::PHONE_INVALID_MESSAGE,
        ];
    }

    /**
     * @return string
     */
    public function getPhoneCian(): string
    {
        return $this->get('phone_cian');
    }

    /**
     * @return string
     */
    public function getPhoneAvito(): string
    {
        return $this->get('phone_avito');
    }

    /**
     * @return string
     */
    public function getPhoneYandex(): string
    {
        return $this->get('phone_yandex');
    }
}
