<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Support\Facades\Http;

class BookTest extends TestCase
{
    use RefreshDatabase;

    // ===== 画面アクセス =====
    public function test_book_index_is_displayed(): void
    {
        $response = $this->get('/books');

        $response->assertStatus(200);
    }

    public function test_book_show_is_displayed(): void
    {
        $book = Book::factory()->create();

        $response = $this->get(route('books.show', $book));

        $response->assertOk();
    }

    public function test_book_create_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('books.create'));

        $response->assertOk();
    }

    public function test_book_edit_is_displayed(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('books.edit', $book));

        $response->assertOk();
    }

    // ===== CRUD =====
    public function test_book_can_be_stored(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $data = [
            'title' => 'Laravel入門',
            'author' => '山田太郎',
            'isbn' => '1234567890123',
            'published_date' => '2026-08-29',
            'genres' => [$genre->id],
        ];

        $response = $this->actingAs($user)
            ->post(route('books.store'), $data);

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'title' => 'Laravel入門',
            'author' => '山田太郎',
            'user_id' => $user->id,
        ]);
    }

    public function test_book_can_be_updated(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
        ]);

        $data = [
            'title' => '更新後のタイトル',
            'author' => '更新後の著者',
            'isbn' => '1234567890123',
            'published_date' => '2026-08-29',
            'genres' => [$genre->id],
        ];

        $response = $this->actingAs($user)
            ->put(route('books.update', $book), $data);

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => '更新後のタイトル',
            'author' => '更新後の著者',
        ]);
    }

    public function test_book_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    // ==== バリデーション ====
    public function test_book_title_is_required(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => '',
                'author' => '山田太郎',
                'isbn' => '1234567890123',
                'published_date' => '2026-08-29',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_book_author_is_required(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel入門',
                'author' => '',
                'isbn' => '1234567890123',
                'published_date' => '2026-08-29',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('author');
    }

    public function test_book_genres_are_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel入門',
                'author' => '山田太郎',
                'isbn' => '1234567890123',
                'published_date' => '2026-08-29',
            ]);

        $response->assertSessionHasErrors('genres');
    }

    public function test_book_isbn_must_be_unique(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        Book::factory()->create([
            'isbn' => '1234567890123',
        ]);

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel入門',
                'author' => '山田太郎',
                'isbn' => '1234567890123',
                'published_date' => '2026-08-29',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('isbn');
    }

    public function test_book_isbn_must_be_13_digits(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel入門',
                'author' => '山田太郎',
                'isbn' => '12345',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('isbn');
    }

    public function test_book_published_date_must_be_date(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel入門',
                'author' => '山田太郎',
                'published_date' => 'invalid-date',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('published_date');
    }

    public function test_book_image_url_must_be_valid_url(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel入門',
                'author' => '山田太郎',
                'image_url' => 'not-url',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('image_url');
    }

    public function test_book_can_be_updated_with_same_isbn(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
            'isbn' => '1234567890123',
        ]);

        $response = $this->actingAs($user)
            ->put(route('books.update', $book), [
                'title' => '更新後タイトル',
                'author' => '山田太郎',
                'isbn' => '1234567890123',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionDoesntHaveErrors('isbn');
    }

    public function test_book_cannot_be_updated_with_duplicate_isbn(): void
    {
        $user = User::factory()->create();
        $genre = Genre::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
            'isbn' => '1111111111111',
        ]);

        Book::factory()->create([
            'isbn' => '2222222222222',
        ]);

        $response = $this->actingAs($user)
            ->put(route('books.update', $book), [
                'title' => '更新後タイトル',
                'author' => '山田太郎',
                'isbn' => '2222222222222',
                'genres' => [$genre->id],
            ]);

        $response->assertSessionHasErrors('isbn');
    }

    // ==== 認可 ====
    public function test_user_cannot_access_other_users_book_edit_page(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)
            ->get(route('books.edit', $book));

        $response->assertForbidden();
    }

    public function test_user_cannot_update_other_users_book(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $genre = Genre::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)
            ->put(route('books.update', $book), [
                'title' => '不正な更新',
                'author' => '山田太郎',
                'isbn' => '1234567890123',
                'genres' => [$genre->id],
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
            'title' => '不正な更新',
        ]);
    }

    public function test_user_cannot_delete_other_users_book(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($otherUser)
            ->delete(route('books.destroy', $book));

        $response->assertForbidden();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);
    }

    // ===== 検索・フィルタ =====
    public function test_books_can_be_searched_by_keyword(): void
    {
        $matchingBook = Book::factory()->create([
            'title' => 'Laravel入門',
            'author' => '山田太郎'
        ]);

        $otherBook = Book::factory()->create([
            'title' => 'PHP実践',
            'author' => '佐藤花子',
        ]);

        $response = $this->get(route('books.index', [
            'keyword' => 'Laravel',
        ]));

        $response->assertOk();
        $response->assertSee($matchingBook->title);
        $response->assertDontSee($otherBook->title);
    }

    public function test_books_can_be_filtered_by_genre(): void
    {
        $genre = Genre::factory()->create([
            'name' => '小説',
        ]);

        $matchingBook = Book::factory()->create();
        $otherBook = Book::factory()->create();

        $matchingBook->genres()->attach($genre->id);

        $response = $this->get(route('books.index', [
            'genre' => $genre->id,
        ]));

        $response->assertOk();
        $response->assertSee($matchingBook->title);
        $response->assertDontSee($otherBook->title);
    }

    // ===== ソート =====
    public function test_books_can_be_sorted_by_title(): void
    {
        $bookB = Book::factory()->create([
            'title' => 'Bの本',
        ]);

        $bookA = Book::factory()->create([
            'title' => 'Aの本',
        ]);

        $response = $this->get(route('books.index', [
            'sort' => 'title',
        ]));

        $response->assertOk();

        $response->assertSeeInOrder([
            $bookA->title,
            $bookB->title,
        ]);
    }

    public function test_books_can_be_sorted_by_rating(): void
    {
        $highRatedBook = Book::factory()->create([
            'title' => '高評価の本',
        ]);

        $lowRatedBook = Book::factory()->create([
            'title' => '低評価の本',
        ]);

        Review::factory()->create([
            'book_id' => $highRatedBook->id,
            'rating' => 5,
        ]);

        Review::factory()->create([
            'book_id' => $lowRatedBook->id,
            'rating' => 3,
        ]);

        $response = $this->get(route('books.index', [
            'sort' => 'rating',
        ]));

        $response->assertOk();

        $response->assertSeeInOrder([
            $highRatedBook->title,
            $lowRatedBook->title,
        ]);
    }

    public function test_books_can_be_sorted_by_latest(): void
    {
        $oldBook = Book::factory()->create([
            'title' => '古い本',
            'created_at' => now()->subDay(),
        ]);

        $newBook = Book::factory()->create([
            'title' => '新しい本',
            'created_at' => now(),
        ]);

        $response = $this->get(route('books.index', [
            'sort' => 'latest',
        ]));

        $response->assertOk();

        $response->assertSeeInOrder([
            $newBook->title,
            $oldBook->title,
        ]);
    }

    public function test_books_can_be_sorted_by_oldest(): void
    {
        $oldBook = Book::factory()->create([
            'title' => '古い本',
            'created_at' => now()->subDay(),
        ]);

        $newBook = Book::factory()->create([
            'title' => '新しい本',
            'created_at' => now(),
        ]);

        $response = $this->get(route('books.index', [
            'sort' => 'oldest',
        ]));

        $response->assertOk();

        $response->assertSeeInOrder([
            $oldBook->title,
            $newBook->title,
        ]);
    }

    public function test_books_can_be_searched_by_author(): void
    {
        $matchingBook = Book::factory()->create([
            'title' => 'PHP入門',
            'author' => '山田太郎',
        ]);

        $otherBook = Book::factory()->create([
            'title' => 'Laravel実践',
            'author' => '佐藤花子',
        ]);

        $response = $this->get(route('books.index', [
            'keyword' => '山田',
        ]));

        $response->assertOk();
        $response->assertSee($matchingBook->title);
        $response->assertDontSee($otherBook->title);
    }

    // ===== ISBN検索 =====
    public function test_book_can_be_searched_by_isbn(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'www.googleapis.com/*' => Http::response([
                'items' => [
                    [
                        'volumeInfo' => [
                            'title' => 'Laravel入門',
                            'authors' => ['山田太郎'],
                            'publishedDate' => '2026-08-29',
                            'imageLinks' => [
                                'thumbnail' => 'https://example.com/book.jpg',
                            ],
                            'description' => 'Laravelの入門書です',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('books.search-isbn', '1234567890123'));

        $response->assertOk();

        $response->assertJson([
            'title' => 'Laravel入門',
            'author' => '山田太郎',
            'published_date' => '2026-08-29',
            'image_url' => 'https://example.com/book.jpg',
            'description' => 'Laravelの入門書です',
        ]);
    }

    public function test_book_isbn_search_returns_404_when_book_is_not_found(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'www.googleapis.com/*' => Http::response([
                'items' => [],
            ], 200),
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('books.search-isbn', '1234567890123'));

        $response->assertNotFound();

        $response->assertJson([
            'error' => '該当するISBNの書籍が見つかりませんでした',
        ]);
    }

    public function test_book_isbn_search_returns_api_error(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'www.googleapis.com/*' => Http::response([], 500),
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('books.search-isbn', '1234567890123'));

        $response->assertStatus(500);

        $response->assertJson([
            'error' => '書籍情報の取得中にエラーが発生しました',
        ]);
    }
}
