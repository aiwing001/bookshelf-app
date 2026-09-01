<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookTest extends TestCase
{

    public function test_book_belongs_to_user_relation(): void
    {
        $book = new Book();
        $relation = $book->user();
        $this->assertInstanceOf(BelongsTo::class, $relation);
    }

    public function test_book_belongs_to_many_genres_relation(): void
    {
        $book = new Book();
        $relation = $book->genres();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
    }

    public function test_book_has_many_reviews_relation(): void
    {
        $book = new Book();
        $relation = $book->reviews();
        $this->assertInstanceOf(HasMany::class, $relation);
    }

    public function test_book_belongs_to_many_favorite_users_relation(): void
    {
        $book = new Book();
        $relation = $book->favoriteUsers();
        $this->assertInstanceOf(BelongsToMany::class, $relation);
    }
}
