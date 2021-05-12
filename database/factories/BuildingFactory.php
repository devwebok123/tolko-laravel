<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Metro;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Building::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->word,
            'name' => $this->faker->name,
            'region_id' => ($rg = Region::factory()->create()->id),
            'metro_id' => ($metros = Metro::factory(3)->create(['region_id' => $rg]))->shift()->id,
            'metro_id_2' => $metros->shift()->id,
            'metro_id_3' => $metros->shift()->id,
            'metro_time' => $this->faker->randomDigitNotNull,
            'metro_time_type' => $this->faker->numberBetween(1, 2),
            'metro_distance' => $this->faker->randomDigitNotNull,
            'mkad_distance' => $this->faker->randomDigitNotNull,
            'year_construction' => $this->faker->year,
            'type' => $this->faker->numberBetween(1, 7),
            'series' => $this->faker->word,
            'ceil_height' => $this->faker->randomFloat(2, 2.99, 9.99),
            'passenger_lift_count' => $this->faker->randomDigitNotNull,
            'cargo_lift_count' => $this->faker->randomDigitNotNull,
            'garbage_chute' => $this->faker->boolean,
            'class' => $this->faker->randomElement(["A","B","C","D"]),
            'floors' => $this->faker->randomDigitNotNull,
            'parking_type' => $this->faker->numberBetween(1, 4),
            'near_infra' => $this->faker->boolean,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
        ];
    }
}
