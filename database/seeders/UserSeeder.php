<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => '山田太郎',
                'email' => 'yamada@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '鈴木花子',
                'email' => 'suzuki@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '田中一郎',
                'email' => 'tanaka@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '佐藤美咲',
                'email' => 'sato@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '高橋健太',
                'email' => 'takahashi@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
