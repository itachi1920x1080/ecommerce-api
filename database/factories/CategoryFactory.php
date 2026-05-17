<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true); // បង្កើតឈ្មោះប្រភេទអក្សរ ២ ម៉ាត់
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(10), // បង្កើតប្រយោគខ្លីៗ
        ];
    }
}