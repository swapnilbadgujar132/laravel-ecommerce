<?php

namespace Database\Seeders;

use App\Models\Slider;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $faker = Factory::create();

      foreach (range(1, 10) as $index) {
       Slider::create([
        'title'=>$faker->title(),
        'details'=>$faker->text(),
        'url'=>$faker->url(),
        'image'=> 'slider_image.jpg',
       ]);
     }
    }
}
