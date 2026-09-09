<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('articles')->insert([
                'title' => substr(file_get_contents('https://lorem-api.com/api/lorem'), 0, 20),
                'content' => file_get_contents('https://lorem-api.com/api/lorem'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
