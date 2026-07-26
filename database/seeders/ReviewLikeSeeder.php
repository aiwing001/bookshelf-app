<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviewLikes = [
            1 => [1, 4],
            2 => [2],
            3 => [],
            4 => [1, 3],
            5 => [3, 5],
            6 => [1],
            7 => [2, 3, 5],
            8 => [],
            9 => [1, 2],
            10 => [2],
            11 => [4],
            12 => [2, 4],
            13 => [3],
            14 => [],
            15 => [1, 5],
            16 => [3],
            17 => [4],
            18 => [1, 2],
            19 => [],
            20 => [5],
            21 => [2, 4],
            22 => [3],
            23 => [],
            24 => [3, 4],
            25 => [4],
            26 => [1, 5],
            27 => [2],
            28 => [],
            29 => [5],
            30 => [2, 5],
            31 => [1],
            32 => [4, 5],
        ];

        foreach ($reviewLikes as $reviewId => $userIds) {
            $review = Review::find($reviewId);

            $review->likedUsers()->syncWithoutDetaching($userIds);
        }
    }
}
