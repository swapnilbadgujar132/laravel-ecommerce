<?php

namespace Database\Seeders;

use App\Models\Blog;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class blogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $feker = Factory::create();
       foreach (range(1,10) as $key => $index) {
       Blog::create([
        'image'=>'blog.jpg',
        'title'=>$feker->title,
        'cat_id'=>1,
        'description'=>'description',
        'tags'=>json_encode(['blog_1']),
        'meta_keyword'=>json_encode(['meta_keywords']),
        'meta_description'=>'meta_description',
     ]);
     }
    }
}
