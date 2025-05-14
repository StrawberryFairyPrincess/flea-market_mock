<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    private static int $sequence = 1;
    public function definition()
    {
        return [
            'item_id' => self::$sequence++,

            'fashion' => $this->faker->numberBetween(0, 1),
            'appliance' => $this->faker->numberBetween(0, 1),
            'interior' => $this->faker->numberBetween(0, 1),
            'lady' => $this->faker->numberBetween(0, 1),
            'man' => $this->faker->numberBetween(0, 1),
            'cosmetic' => $this->faker->numberBetween(0, 1),
            'book' => $this->faker->numberBetween(0, 1),
            'game' => $this->faker->numberBetween(0, 1),
            'sport' => $this->faker->numberBetween(0, 1),
            'kitchen' => $this->faker->numberBetween(0, 1),
            'handmade' => $this->faker->numberBetween(0, 1),
            'accessory' => $this->faker->numberBetween(0, 1),
            'toy' => $this->faker->numberBetween(0, 1),
            'child' => $this->faker->numberBetween(0, 1)

        ];
    }
}
