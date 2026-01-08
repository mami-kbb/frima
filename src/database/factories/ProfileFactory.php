<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    Protected $model = Profile::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'profile_image' => 'dummy.jpg',
            'postal_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building' => $this->faker->secondaryAddress(),
        ];
    }
}
