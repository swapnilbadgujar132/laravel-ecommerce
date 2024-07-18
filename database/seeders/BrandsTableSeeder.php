<?php

namespace Database\Seeders;

use App\Models\Brand;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $faker = Factory::create();

      foreach (range(1,10) as  $index) {
      Brand::create([
        'image'=>$faker->image(),
        'name'=>$faker->name(),
        'slug'=>$faker->text(),
        'status'=>true,
      ]);
      }
    }
}
