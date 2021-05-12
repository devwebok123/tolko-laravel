<?php

namespace Database\Seeders;

use App\Models\AdmArea;
use Illuminate\Database\Seeder;

class AdmAreaSeeder extends Seeder
{
    const KEYS = ['name', 'short_name'];

    const VALS = [
        ['Центральный', 'ЦАО'],
        ['Северо-восточный', 'СВАО'],
        ['Восточный', 'ВАО'],
        ['Юго-западный', 'ЮЗАО'],
        ['Юго-восточный', 'ЮВАО'],
        ['Южный', 'ЮАО'],
        ['Северный', 'САО'],
        ['Западный', 'ЗАО'],
        ['Северо-западный', 'СЗАО'],
        ['Новомосковский', 'ТИНАО'],
        ['Московская область', 'МО'],
        ['Зеленоградский', 'Троицкий'],
        ['Троицкий', 'Зеленоградский'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        array_map(function ($a) {
            AdmArea::query()->insert(array_combine(self::KEYS, $a));
        }, self::VALS);
    }
}
