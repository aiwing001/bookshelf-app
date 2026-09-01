<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    // ===== 画面アクセス =====
    public function test_ranking_index_is_displayed(): void
    {
        $response = $this->get(route('ranking.index'));

        $response->assertOk();
    }

    // ===== ランキング =====
    public function test_books_are_ranked_by_average_rating(): void
    {
        $highRatedBook = Book::factory()->create();
        $lowRatedBook = Book::factory()->create();

        Review::factory()->create([
            'book_id' => $highRatedBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'book_id' => $lowRatedBook->id,
            'rating' => 3,
        ]);

        $response = $this->get(route('ranking.index'));

        $response->assertOk();

        $response->assertSeeInOrder([
            $highRatedBook->title,
            $lowRatedBook->title,
        ]);
    }
}
