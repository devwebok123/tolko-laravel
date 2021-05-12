<?php

namespace Database\Factories;

use App\Models\AdmArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdmAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdmArea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'short_name' => $this->faker->word,
        ];
    }
}
