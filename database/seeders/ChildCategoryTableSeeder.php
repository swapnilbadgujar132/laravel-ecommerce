<?php

namespace Database\Seeders;

use App\Models\ChildCategory;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChildCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        
        foreach (range(1,2) as  $index) {
            ChildCategory::create([
                'cat_id'=>1,
                'sub_cat_id'=>1,
                'name'=>$faker->name,
                'slug'=>$faker->slug,
                'status'=>1,
            ]);
        }
    }
}
