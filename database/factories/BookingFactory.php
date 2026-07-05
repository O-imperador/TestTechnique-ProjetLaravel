<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(1, 14).' days');

        return [
            'user_id' => \App\Models\User::factory(),
            'property_id' => \App\Models\Property::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
            'total_price' => fake()->numberBetween(100, 5000),
        ];
    }
}
