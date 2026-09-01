<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        $comments = [
            1 => 'あまり自分には合わない内容でした。',
            2 => '少し物足りなさを感じました。',
            3 => '全体的に楽しめる内容でした。',
            4 => 'とても面白く、満足できる内容でした。',
            5 => '非常に素晴らしく、ぜひ皆さんにおすすめしたい一冊です。',
        ];

        foreach ($books as $book) {
            $reviewCount = rand(2, 4);

            $reviewers = $users->random($reviewCount);

            foreach ($reviewers as $user) {
                $rating = rand(1, 5);

                Review::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'rating' => $rating,
                    'comment' => $comments[$rating],
                ]);
            }
        }
    }
}
