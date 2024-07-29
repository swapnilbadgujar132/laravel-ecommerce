<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 4,
                'image' => 'category/OXqPUDyHDf3xSuUbxCdnFMPVYE6r3yz0WGIH97YC.jpg',
                'name' => 'T-shirts',
                'slug' => 'men-T-shirts',
                'meta_keyword' => '[{"value":"men"},{"value":"clothing"},{"value":"t-shirt"},{"value":"brand"}]',
                'meta_description' => 'men t-shirts',
                'serial' => 1,
                'status' => 1,
                'created_at' => '2023-08-11 13:11:48',
                'updated_at' => '2023-08-11 13:11:48',
            ],
        ];

        foreach ($data as $row) {
            DB::table('categories')->insert($row);
        }
    }
}
