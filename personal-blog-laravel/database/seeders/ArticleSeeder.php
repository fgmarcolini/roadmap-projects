<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $writerRole = Role::findOrCreate('writer');
        $lorem = 'https://jaspervdj.be/lorem-markdownum/markdown.txt';

        $users = User::count() < 3
            ? collect([
                User::create([
                    'name' => 'Alice Developer',
                    'email' => 'alice@example.com',
                    'password' => bcrypt('password'),
                ])->assignRole($writerRole),
                User::create([
                    'name' => 'Bob Builder',
                    'email' => 'bob@example.com',
                    'password' => bcrypt('password'),
                ])->assignRole($writerRole),
                User::create([
                    'name' => 'Charlie Coder',
                    'email' => 'charlie@example.com',
                    'password' => bcrypt('password'),
                ])->assignRole($writerRole),
            ])
            : User::take(3)->get();

        $matches = [
            'Argentina vs Spain',
            'Argentina vs England',
            'Spain vs Portugal',
            'Spain vs Craotia',
            'Spain vs Cape Verde',
            'France vs Spain',
            'France vs Morocco',
            'England vs Mexico',
            'England vs Norway',
            'Argentina vs Egypt',
            'Argentina vs Cape Verde',
            'Argentina vs Switzerland',
            'Uruguay vs Cape Verde',
        ];

        $i = 0;
        foreach ($matches as $match) {
            $users->random()->articles()->create([
                'title' => $match,
                'content' => file_get_contents($lorem),
                'dop' => date('Y-m-d', strtotime(Carbon::today()->subDays($i))),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $i++;
        }
    }
}
