<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ReadingPlan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingPlanTest extends TestCase
{
    public function test_reading_plan_belongs_to_user(): void
    {
        $readingPlan = new ReadingPlan();

        $this->assertInstanceOf(
            BelongsTo::class,
            $readingPlan->user()
        );
    }

    public function test_reading_plan_belongs_to_book(): void
    {
        $readingPlan = new ReadingPlan();

        $this->assertInstanceOf(
            BelongsTo::class,
            $readingPlan->book()
        );
    }
}
