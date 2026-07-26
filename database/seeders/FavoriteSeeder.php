<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $favorites = [
            1 => [2, 3, 8, 10],
            2 => [1, 4, 7],
            3 => [2, 5, 6, 11],
            4 => [3, 8, 9],
            5 => [1, 6, 10, 11, 4],
        ];

        foreach ($favorites as $userId => $bookIds) {
            $user = User::find($userId);

            $user->favoriteBooks()->syncWithoutDetaching($bookIds);
        }
    }
}
