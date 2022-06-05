<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::insert([
            [
                'id' => 1,
                'status' => 1,
                'category' => '1,2,3',
                'post_date' => now(),
                'title' => 'タイトル1（公開）',
                'subtitle' => 'サブタイトル1',
                'content' => '<p>本文1</p>',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'status' => 2,
                'category' => '1,2,3',
                'post_date' => now(),
                'title' => 'タイトル2（非公開）',
                'subtitle' => 'サブタイトル2',
                'content' => '<p>本文2</p>',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'status' => 1,
                'category' => '1,2,3',
                'post_date' => now(),
                'title' => 'タイトル3（公開）',
                'subtitle' => 'サブタイトル3',
                'content' => '<p>本文3</p>',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
