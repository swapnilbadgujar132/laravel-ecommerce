<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;

class productTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        foreach (range(1,5) as  $index) {
        Product::create([
          'name'=>$faker->name(),
          'slug'=>$faker->slug(),
          'featured_image'=>'product_0.jpg',
          'images' => json_encode(['', '']),
          'short_description'=>"short_description",
          'description'=>"description",
          'tags'=>json_encode(['T-shirts product']),
          'specifications'=>'null',
          'meta_keyword'=>json_encode(['T-shirts NEW']),
          'meta_description'=>'meta_description',
          'current_price'=>'300',
          'previous_price'=>'400',
          'cat_id'=>1,
          'sub_cat_id'=>1,
          'child_cat_id'=>1,
          'brand_id'=>1,
          'total_stock'=>10,
          'status'=>1,
        ]);
        }   
     }
}
