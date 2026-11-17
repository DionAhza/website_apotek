<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicine>
 */
class MedicineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'price' => fake()->numberBetween(5, 200) * 1000,
            'description' => fake()->paragraph(),
            'expired' => now()->addMonths(fake()->numberBetween(6, 36))->toDateString(),
            'medicine_image' => null,
            'category_id' => Category::factory(),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => ['expired' => now()->subDays(10)->toDateString()]);
    }

    public function expiringSoon(): static
    {
        return $this->state(fn () => ['expired' => now()->addDays(30)->toDateString()]);
    }
}
