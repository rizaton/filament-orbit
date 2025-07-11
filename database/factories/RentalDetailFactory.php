<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RentalDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_rental' => Rental::factory(),
            'id_item' => Item::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'is_returned' => false,
        ];
    }
}
