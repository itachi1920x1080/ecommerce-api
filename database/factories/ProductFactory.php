<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true); 
        $price = fake()->randomFloat(2, 10, 500); // តម្លៃចន្លោះពី ១០ ទៅ ៥០០ ដុល្លារ

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku' => 'PROD-' . fake()->unique()->numberBetween(1000, 9999),
            'regular_price' => $price,
            'discount_price' => fake()->boolean(30) ? $price - ($price * 0.1) : null, // 30% នៃផលិតផលមានបញ្ចុះតម្លៃ
            'qty' => fake()->numberBetween(0, 100),
            'description' => fake()->paragraph(),
            // មិនបាច់ដាក់រូបសិនទេ ទុក null
        ];
    }
}