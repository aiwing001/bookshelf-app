<?php

namespace Tests\Unit;

use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    public function test_review_belongs_to_user(): void
    {
        $review = new Review;

        $this->assertInstanceOf(
            BelongsTo::class,
            $review->user()
        );
    }

    public function test_review_belongs_to_book(): void
    {
        $review = new Review;

        $this->assertInstanceOf(
            BelongsTo::class,
            $review->book()
        );
    }

    public function test_review_belongs_to_many_liked_by_users(): void
    {
        $review = new Review;

        $this->assertInstanceOf(
            BelongsToMany::class,
            $review->likedByUsers()
        );
    }
}
