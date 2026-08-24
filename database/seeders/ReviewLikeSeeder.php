<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewLikeSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = Review::all();
        $userIds = User::pluck('id');

        foreach ($reviews as $review) {
            $likeCount = rand(0, min(3, $userIds->count()));

            if ($likeCount === 0) {
                continue;
            }

            $likedUserIds = $userIds
                ->random($likeCount)
                ->values()
                ->all();

            $review->likedByUsers()->syncWithoutDetaching($likedUserIds);
        }
    }
}
