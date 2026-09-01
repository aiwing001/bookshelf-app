<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    // ===== 画面アクセス =====
    public function test_report_index_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertOk();
    }

    // ===== 集計 =====
    public function test_report_contains_correct_review_statistics(): void
    {
        $user = User::factory()->create();

        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book1->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book2->id,
            'rating' => 3,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertOk();

        $response->assertViewHas('stats', function ($stats) {
            return $stats['summary']['total_reviews'] === 2
                && $stats['summary']['books_read'] === 2
                && $stats['summary']['average_rating'] == 4;
        });
    }

    // ===== 高評価書籍 =====
    public function test_report_contains_only_top_rated_books(): void
    {
        $user = User::factory()->create();

        $highRatedBook = Book::factory()->create([
            'title' => '高評価の本',
        ]);

        $lowRatedBook = Book::factory()->create([
            'title' => '低評価の本',
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $highRatedBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $lowRatedBook->id,
            'rating' => 3,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertOk();

        $response->assertViewHas('stats', function ($stats) use ($highRatedBook, $lowRatedBook) {
            $titles = $stats['top_rated_books']->pluck('title');

            return $titles->contains($highRatedBook->title)
                && ! $titles->contains($lowRatedBook->title);
        });
    }

    // ===== ジャンル別評価 =====
    public function test_report_contains_genre_rating_statistics(): void
    {
        $user = User::factory()->create();

        $genre = Genre::factory()->create([
            'name' => '小説',
        ]);

        $book = Book::factory()->create();
        $book->genres()->attach($genre->id);

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertOk();

        $response->assertViewHas('stats', function ($stats) use ($genre) {
            $genreRating = $stats['genre_ratings']->firstWhere('id', $genre->id);

            return $genreRating !== null
                && $genreRating['name'] === '小説'
                && $genreRating['count'] === 1
                && $genreRating['average_rating'] == 5;
        });
    }

    // ===== ユーザーごとの集計 =====
    public function test_report_does_not_include_other_users_reviews(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userBook = Book::factory()->create();
        $otherBook = Book::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'book_id' => $userBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'user_id' => $otherUser->id,
            'book_id' => $otherBook->id,
            'rating' => 1,
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index'));

        $response->assertOk();

        $response->assertViewHas('stats', function ($stats) {
            return $stats['summary']['total_reviews'] === 1
                && $stats['summary']['books_read'] === 1
                && $stats['summary']['average_rating'] == 5;
        });
    }
}
