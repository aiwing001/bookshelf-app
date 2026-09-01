<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\Genre;
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
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'author',
                        'isbn',
                        'published_date',
                        'image_url',
                        'description',
                        'genres',
                        'reviews_count',
                    ],
                ],
                'links',
                'meta',
            ])
            ->assertJsonFragment([
                'id' => $books->first()->id,
                'title' => $books->first()->title,
            ]);
    }

    // ===== キーワード検索 =====
    public function test_books_can_be_searched_by_keyword(): void
    {
        Book::factory()->create([
            'title' => 'Laravel入門',
            'author' => '山田太郎',
        ]);

        Book::factory()->create([
            'title' => 'PHP入門',
            'author' => '佐藤花子',
        ]);

        $response = $this->getJson('/api/v1/books?keyword=Laravel');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'title' => 'Laravel入門',
            ])
            ->assertJsonMissing([
                'title' => 'PHP入門',
            ]);
    }

    // ===== ジャンル絞り込み =====
    public function test_books_can_be_filtered_by_genre(): void
    {
        $genre = Genre::factory()->create();

        $targetBook = Book::factory()->create();
        $targetBook->genres()->attach($genre->id);

        $otherBook = Book::factory()->create();

        $response = $this->getJson("/api/v1/books?genre={$genre->id}");

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $targetBook->id,
            ])
            ->assertJsonMissing([
                'id' => $otherBook->id,
            ]);
    }

    // ===== ページネーション =====
    public function test_books_can_be_paginated(): void
    {
        Book::factory()->count(8)->create();

        $response = $this->getJson('/api/v1/books?per_page=5');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 8);
    }

    // ===== 不正なジャンル =====
    public function test_invalid_genre_returns_validation_error(): void
    {
        $response = $this->getJson('/api/v1/books?genre=999999');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['genre']);
    }

    // ===== 詳細取得 =====
    public function test_book_can_be_retrieved(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/v1/books/{$book->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'author',
                    'isbn',
                    'published_date',
                    'image_url',
                    'description',
                    'genres',
                    'reviews',
                ],
            ])
            ->assertJsonFragment([
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
            ]);
    }
}
