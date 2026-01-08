<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'buyer_id' => User::factory(),
            'item_id' => Item::factory(),
            'postal_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'building' => $this->faker->secondaryAddress(),
            'payment_method' => $this->faker->randomElement([
                'convenience',
                'card',
            ]),
            'total_price' => $this->faker->numberBetween(0,100000),
        ];
    }
}
