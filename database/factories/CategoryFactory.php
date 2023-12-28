<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

            return [
                'name' => fake()->unique()->name(),
                'slug' => fake()->unique()->name(),
                'description' => fake()->paragraph(),
                'image'    => 'https://source.unsplash.com/random/?category?'.rand(1,20),
                'is_visible'=> true,
        ];
    }
}
