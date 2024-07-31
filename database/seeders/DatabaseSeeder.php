<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
     /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Admin::factory(1)->create([
            'username' => 'Swapnil',
        ]);
        // Admin ::factory(1)->create();
        $this->call([
            ManageSitesTableSeeder::class,
            CategoriesTableSeeder::class,
            BrandsTableSeeder::class,
            ServicesTableSeeder::class,
            SliderTableSeeder::class,
            subCategoryTableSeeder::class,
            ChildCategoryTableSeeder::class,
            productTableSeeder::class,
            blogsCategoryTableSeeder::class,
            blogsTableSeeder::class,
        ]);


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

