<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = Category::pluck('id')->toArray();

        return [
            'category_id' => fake()->randomElement($categories),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => fake()->numberBetween(1, 3),
            'email' => fake()->safeEmail(),
            'tel' => fake()->numerify('###########'),
            'address' => fake()->address(),
            'building' => fake()->secondaryAddress(),
            'detail' => fake()->realText(100),
        ];
    }
}
