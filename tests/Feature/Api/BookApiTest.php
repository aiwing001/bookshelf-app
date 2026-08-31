<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    // ===== 一覧取得 =====
    public function test_books_can_be_retrieved(): void
    {
        $books = Book::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/books');

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonFragment([
                'id' => $books->first()->id,
                'title' => $books->first()->title,
            ]);
    }

    // ===== 詳細取得 =====
    public function test_book_can_be_retrieved(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
            ]);
    }
}