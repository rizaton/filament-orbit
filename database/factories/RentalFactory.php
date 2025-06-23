<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'status' => fake()->randomElement([
                'pending',
                'approved',
                'rented',
                'rejected',
                'returning',
                'returned',
                'late'
            ]),
            'down_payment' => fake()->randomFloat(2, 0, 1000000),
            'rent_date' => fake()->date(),
            'return_date' => fake()->date(),
            'late_date' => fake()->optional()->date(),
            'late_fees' => fake()->optional()->randomFloat(2, 0, 1000000),
            'total_fees' => fake()->optional()->randomFloat(2, 0, 1000000),
        ];
    }
}
