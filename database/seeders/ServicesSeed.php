<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class ServicesTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        foreach (range(1, 10) as $index) {
            Service::create([
                'image'=>public_path('media/3489823-min_1702376212.jpg'),
                'title' => $faker->sentence(),
                'details' => $faker->paragraph(),
            ]);
        }
    }
}


