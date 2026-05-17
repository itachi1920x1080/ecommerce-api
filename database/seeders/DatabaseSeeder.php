<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ១. បង្កើត Categories ចំនួន ១០
        Category::factory(10)->create();

        // ២. បង្កើត Products ចំនួន ៥០
        Product::factory(50)->create();

        echo "ទិន្នន័យគំរូ (Fake Data) ត្រូវបានបាញ់ចូលជោគជ័យ! \n";
    }
}   