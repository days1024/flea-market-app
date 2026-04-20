<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('ja_JP');
        return [
            'profile_image' =>$faker->randomElement([
            'profile_images/senior-camera_232781.png',
            'profile_images/smartphone-in-bed_male_23205.png',
            'profile_images/smartphone-in-bed_woman_23204.png',
            ]),
            'user_name' => $faker->name(),
            'post_code' => $faker->numerify('###-####'),
            'address' => $faker->address(),
            'building' => $faker->secondaryAddress(),
        ];
    }
}
