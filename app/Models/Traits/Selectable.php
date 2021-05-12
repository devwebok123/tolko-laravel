<?php

namespace App\Models\Traits;

use App\Models\Block;
use Illuminate\Database\Eloquent\Collection;

trait Selectable
{
    /**
     * @param bool $nullable
     * @return array for select options in form
     */
    public static function options(bool $nullable = true): array
    {
        return self::buildOptions(self::get(), $nullable);
    }

    /**
     * @param array $arrayKeys
     * @param string $langElement
     * @return array
     */
    public static function optionsLang($arrayKeys, $langElement): array
    {
        foreach ($arrayKeys as $key => $value) {
            $arrayKeys [$key] = __($langElement . '.' . $value);
        }

        return $arrayKeys;
    }

    /**
     * @param array $currentOptions
     * @param array $options
     * @param string $langElement
     * @return array
     */
    public static function currentOptionsLang(array $currentOptions, array $options, string $langElement): array
    {
        $all = self::optionsLang($options, $langElement);

        $result = [];
        foreach ($currentOptions as $item) {
            $result[] = $all[$item];
        }

        return $result;
    }

    /**
     * @param Collection $collection
     * @param bool $nullable
     * @return array
     */
    public static function collectionOptions(Collection $collection, bool $nullable = true): array
    {
        return self::buildOptions($collection, $nullable);
    }

    /**
     * @param Collection $collection
     * @param bool $nullable
     * @return array
     */
    protected static function buildOptions(Collection $collection, bool $nullable = true): array
    {
        $options = $collection->pluck('name', 'id');
        if ($nullable) {
            $options->prepend(trans('global.pleaseSelect'), '');
        }
        return $options->toArray();
    }
}
