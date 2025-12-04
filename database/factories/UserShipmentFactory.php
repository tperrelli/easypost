<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserShipment>
 */
class UserShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'shipment_id' => 'shp_' . fake()->unique()->lexify('???????'),
            'status' => fake()->randomElement(['created', 'in_transit', 'deleted']),
        ];
    }
}
