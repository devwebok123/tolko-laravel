<?php

namespace Database\Factories;

use App\Models\Metro;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetroFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Metro::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'line' => $this->faker->randomDigitNotNull,
            'region_id' => Region::factory(),
            'cian_id' => $this->faker->randomNumber(),
        ];
    }
}
