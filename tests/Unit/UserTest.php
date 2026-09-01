<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_has_many_books(): void
    {
        $user = new User;

        $this->assertInstanceOf(
            HasMany::class,
            $user->books()
        );
    }

    public function test_user_has_many_reviews(): void
    {
        $user = new User;

        $this->assertInstanceOf(
            HasMany::class,
            $user->reviews()
        );
    }

    public function test_user_has_many_reading_plans(): void
    {
        $user = new User;

        $this->assertInstanceOf(
            HasMany::class,
            $user->readingPlans()
        );
    }

    public function test_user_belongs_to_many_favorite_books(): void
    {
        $user = new User;

        $this->assertInstanceOf(
            BelongsToMany::class,
            $user->favoriteBooks()
        );
    }

    public function test_user_belongs_to_many_liked_reviews(): void
    {
        $user = new User;

        $this->assertInstanceOf(
            BelongsToMany::class,
            $user->likedReviews()
        );
    }
}
