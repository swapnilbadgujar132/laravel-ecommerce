<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class blogsCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feker = Factory::create();
        foreach (range(1,1) as $key => $index) {
        BlogCategory::create([
         'name'=>$feker->title,
         'slug'=>$feker->slug,
         'status'=>1,
      ]);
      }
     }
}
