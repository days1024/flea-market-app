<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'image' => 'images/default.jpg',
        'condition' => 'excellent',
        'name' => $this->faker->word(),
        'brand' => 'Rolex',
        'description' => $this->faker->sentence(),
        'price' => $this->faker->numberBetween(1000, 50000),
        'user_id' => null,
        'buyer_id' => null,
        'address_id' => null,

    ];
    }
}
