<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ===== 書籍 =====
    public function test_guest_cannot_access_book_create(): void
    {
        $response = $this->get(route('books.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_store_book(): void
    {
        $response = $this->post(route('books.store'), []);

        $response->assertRedirect(route('login'));
    }

    // ===== お気に入り =====
    public function test_guest_cannot_access_favorites(): void
    {
        $response = $this->get(route('favorites.index'));

        $response->assertRedirect(route('login'));
    }

    // ===== レビュー =====
    public function test_guest_cannot_store_review(): void
    {
        $book = Book::factory()->create();

        $response = $this->post(route('reviews.store', $book), []);

        $response->assertRedirect(route('login'));
    }

    // ===== ジャンル =====
    public function test_guest_cannot_access_genres(): void
    {
        $response = $this->get(route('genres.index'));

        $response->assertRedirect(route('login'));
    }

    // ===== 通知 =====
    public function test_guest_cannot_access_notifications(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login'));
    }

    // ===== レポート =====
    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get(route('reports.index'));

        $response->assertRedirect(route('login'));
    }

    // ===== 読書計画 =====
    public function test_guest_cannot_access_reading_plans(): void
    {
        $response = $this->get(route('reading-plans.index'));

        $response->assertRedirect(route('login'));
    }
}
