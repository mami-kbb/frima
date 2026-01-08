<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'image' => 'dummy.jpg',
            'condition_id' => Condition::factory(),
            'brand_name' => $this->faker->company(),
            'description' => $this->faker->text(225),
            'price' => $this->faker->numberBetween(0,100000),
            'status' => 0,
        ];
    }

    public function sold()
    {
        return $this->state(fn () => [
            'status' => 1,
        ]);
    }
}
