<?php

namespace Tests\Feature;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\ReadingPlan;
use App\Models\User;
use App\Notifications\ReadingPlanReminderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReminderBatchTest extends TestCase
{
    use RefreshDatabase;

    // ===== 3日前通知 =====
    public function test_notification_is_sent_three_days_before_due_date(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDays(3)->toDateString(),
            'status' => ReadingPlanStatus::InProgress,
        ]);

        $this->artisan('app:check-reading-plans')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            ReadingPlanReminderNotification::class
        );
    }

    // ===== 当日通知 =====
    public function test_notification_is_sent_on_due_date(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->toDateString(),
            'status' => ReadingPlanStatus::InProgress,
        ]);

        $this->artisan('app:check-reading-plans')
            ->assertExitCode(0);

        Notification::assertSentTo(
            $user,
            ReadingPlanReminderNotification::class
        );
    }

    // ===== 完了済みは通知しない =====
    public function test_completed_reading_plan_is_not_notified(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDays(3)->toDateString(),
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);

        $this->artisan('app:check-reading-plans')
            ->assertExitCode(0);

        Notification::assertNotSentTo(
            $user,
            ReadingPlanReminderNotification::class
        );
    }

    // ===== 期限切れは通知しない =====
    public function test_expired_reading_plan_is_not_notified(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->toDateString(),
            'status' => ReadingPlanStatus::Expired,
        ]);

        $this->artisan('app:check-reading-plans')
            ->assertExitCode(0);

        Notification::assertNotSentTo(
            $user,
            ReadingPlanReminderNotification::class
        );
    }

    // ===== 通知対象日以外は通知しない =====
    public function test_reading_plan_outside_reminder_dates_is_not_notified(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $book = Book::factory()->create();

        ReadingPlan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'target_date' => now()->addDays(7)->toDateString(),
            'status' => ReadingPlanStatus::InProgress,
        ]);

        $this->artisan('app:check-reading-plans')
            ->assertExitCode(0);

        Notification::assertNotSentTo(
            $user,
            ReadingPlanReminderNotification::class
        );
    }
}
