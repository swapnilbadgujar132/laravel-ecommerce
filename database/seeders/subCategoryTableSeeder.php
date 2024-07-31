<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class subCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        
        foreach (range(1,2) as  $index) {
            SubCategory::create([
                'cat_id'=>1,
                'name'=>$faker->name,
                'slug'=>$faker->slug,
                'status'=>1,
            ]);
        }
    }
}
