<?php


namespace App\Helpers;

class PhoneHelper
{

    /**
     * @param string $phone
     * @return int
     */
    public static function preparePhone(string $phone): int
    {
        return preg_replace('/\D/', '', $phone);
    }
}
