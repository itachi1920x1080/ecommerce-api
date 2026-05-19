<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 👑 ១. បង្កើតគណនីសម្រាប់ Admin ទុកតេស្ត
        User::factory()->create([
            'name' => 'Admin Visal',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'), // លេខសម្ងាត់គឺ admin123
            'role' => 'admin', // 🎯 កំណត់សិទ្ធិជា Admin ចំៗ
        ]);

        // 🧑‍💼 ២. បង្កើតគណនីសម្រាប់ User ធម្មតាទុកតេស្ត
        User::factory()->create([
            'name' => 'Mao Visal',
            'email' => 'visal@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'user', // សិទ្ធិធម្មតា
        ]);

        // 📦 ៣. បង្កើត Category និង Product (របស់ចាស់បងមានស្រាប់)
        Category::factory(10)->create();
        Product::factory(50)->create();
    }
}