<?php

namespace Tests\Feature;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpiredBatchTest extends TestCase
{
    use RefreshDatabase;

    // ===== 期限切れの読書計画を失効 =====
    public function test_overdue_in_progress_reading_plan_becomes_expired(): void
    {
        $readingPlan = ReadingPlan::factory()->create([
            'target_date' => now()->subDay()->toDateString(),
            'status' => ReadingPlanStatus::InProgress,
        ]);

        $this->artisan('reading-plans:update-expired')
            ->assertExitCode(0);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $readingPlan->id,
            'status' => 'expired',
        ]);
    }

    // ===== 期限前は失効しない =====
    public function test_future_in_progress_reading_plan_does_not_become_expired(): void
    {
        $readingPlan = ReadingPlan::factory()->create([
            'target_date' => now()->addDay()->toDateString(),
            'status' => ReadingPlanStatus::InProgress,
        ]);

        $this->artisan('reading-plans:update-expired')
            ->assertExitCode(0);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $readingPlan->id,
            'status' => 'in_progress',
        ]);
    }

    // ===== 当日は失効しない =====
    public function test_reading_plan_due_today_does_not_become_expired(): void
    {
        $readingPlan = ReadingPlan::factory()->create([
            'target_date' => now()->toDateString(),
            'status' => ReadingPlanStatus::InProgress,
        ]);

        $this->artisan('reading-plans:update-expired')
            ->assertExitCode(0);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $readingPlan->id,
            'status' => 'in_progress',
        ]);
    }

    // ===== 完了済みは失効しない =====
    public function test_completed_reading_plan_does_not_become_expired(): void
    {
        $readingPlan = ReadingPlan::factory()
            ->completed()
            ->create([
                'target_date' => now()->subDay()->toDateString(),
            ]);

        $this->artisan('reading-plans:update-expired')
            ->assertExitCode(0);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $readingPlan->id,
            'status' => 'completed',
        ]);
    }

    // ===== すでに失効済みならそのまま =====
    public function test_expired_reading_plan_remains_expired(): void
    {
        $readingPlan = ReadingPlan::factory()
            ->expired()
            ->create([
                'target_date' => now()->subDay()->toDateString(),
            ]);

        $this->artisan('reading-plans:update-expired')
            ->assertExitCode(0);

        $this->assertDatabaseHas('reading_plans', [
            'id' => $readingPlan->id,
            'status' => 'expired',
        ]);
    }
}
