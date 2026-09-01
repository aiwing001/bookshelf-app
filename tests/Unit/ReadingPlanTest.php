<?php

namespace Tests\Unit;

use App\Models\ReadingPlan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ReadingPlanTest extends TestCase
{
    public function test_reading_plan_belongs_to_user(): void
    {
        $readingPlan = new ReadingPlan;

        $this->assertInstanceOf(
            BelongsTo::class,
            $readingPlan->user()
        );
    }

    public function test_reading_plan_belongs_to_book(): void
    {
        $readingPlan = new ReadingPlan;

        $this->assertInstanceOf(
            BelongsTo::class,
            $readingPlan->book()
        );
    }
}
