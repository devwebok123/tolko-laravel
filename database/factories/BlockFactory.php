<?php

namespace Database\Factories;

use App\Models\Block;
use App\Models\Building;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Block::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $filling = $this->faker->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9], $this->faker->numberBetween(1, 9));
        $livingConds = $this->faker->randomElements([1, 2, 3, 4, 5], $this->faker->numberBetween(1, 5));
        $showerBath = $this->faker->randomElements([1, 2], $this->faker->numberBetween(1, 2));
        $included = $this->faker->randomElements([1, 2, 3], $this->faker->numberBetween(1, 3));

        sort($filling);
        sort($showerBath);
        sort($livingConds);
        sort($included);

        $status = $this->faker->numberBetween(1, 3);
        $cian = $this->faker->numberBetween(1, 4);
        $bet = $this->faker->numberBetween(0, 1000);
        if ($status != Block::STATUS_ACTIVE) {
            $cian = null;
            $bet = null;
        }

        return [
            'building_id' => ($building = Building::factory()->create())->id,
            'floor' => $this->faker->numberBetween(0, $building->floors),
            'flat_number' => $this->faker->randomDigitNotNull,
            'area' => $this->faker->randomFloat(2, 0, 999.99),
            'living_area' => $this->faker->randomFloat(2, 0, 999.99),
            'kitchen_area' => $this->faker->randomFloat(2, 0, 99.99),
            'type' => $this->faker->numberBetween(1, 2),
            'rooms' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 9]),
            'rooms_type' => $this->faker->numberBetween(1, 3),
            'balcony' => $this->faker->numberBetween(1, 5),
            'windowsInOut' => $this->faker->numberBetween(1, 3),
            'separate_wc_count' => $this->faker->numberBetween(1, 5),
            'combined_wc_count' => $this->faker->numberBetween(1, 5),
            'renovation' => $this->faker->numberBetween(1, 3),
            'filling' => $filling,
            'shower_bath' => $showerBath,
            'living_conds' => $livingConds,
            'included' => $included,
            'tenant_count_limit' => $this->faker->numberBetween(1, 9),
            'cadastral_number' => $this->faker->word,
            'description' => $this->faker->text,
            'comment' => $this->faker->word,
            'video_url' => $this->faker->word,
            'status' => $status,
            'out_of_market' => (int)$this->faker->boolean,
            'cost' => $this->faker->numberBetween(1000, 1000000),
            'currency' => $this->faker->numberBetween(1, 2),
            'contract_signed' => (int)$this->faker->boolean,
            'commission' => $this->faker->randomFloat(2, 0, 999),
            'commission_type' => $this->faker->numberBetween(1, 2),
            'commission_comment' => $this->faker->word,
            'parking_cost' => $this->faker->randomFloat(2, 0, 99999.99),
            'bargain' => $this->faker->randomFloat(2, 0, 99999999.99),
            'cian' => $cian,
            'bet' => $bet,
            'ad_title' => $this->faker->word,
        ];
    }
}
