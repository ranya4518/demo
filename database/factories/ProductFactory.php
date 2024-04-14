<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),  // Generating a unique three-word name
            'description' => $this->faker->sentence,  // Generating a random sentence for the description
            'price' => $this->faker->randomFloat(2, 1, 1000),  // Generating a random price between 1 and 1000 with 2 decimal points
        ];
    }
}
