<?php

namespace Database\Factories;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {




        return [
            'name' => fake()->unique()->name(20),
            'slug' => fake()->unique()->name(20),
            'description' => fake()->paragraph(),
            'price'    => fake()->randomFloat(2,0,10000),
            'image'    => 'https://source.unsplash.com/random/?brand?'.rand(1,20),
            'images' => ['https://source.unsplash.com/random/?brand?'.rand(1,20),'https://source.unsplash.com/random/?brand?'.rand(1,20),'https://source.unsplash.com/random/?brand?'.rand(1,20),'https://source.unsplash.com/random/?brand?'.rand(1,20)],
            'is_visible'=> true,
            'brand_id' => rand(1,20),
            'quantity' => rand(1,100000),
            'published_at' => now(),
        ];
    }
}
