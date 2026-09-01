<?php

namespace App\Console\Commands;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use App\Notifications\ReadingPlanReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckReadingPlans extends Command
{
    protected $signature = 'app:check-reading-plans';

    protected $description = '読書計画のリマインド通知を送信する';

    /**
     * 読書期限が3日前および当日の読書計画へ
     * リマインド通知を送信する。
     */
    public function handle(): int
    {
        $plans = ReadingPlan::where(
            'status',
            ReadingPlanStatus::InProgress
        )
            ->whereDate('target_date', Carbon::today()->addDays(3))
            ->get();

        foreach ($plans as $readingPlan) {
            $readingPlan->user->notify(
                new ReadingPlanReminderNotification($readingPlan, '3days')
            );
        }

        $plans = ReadingPlan::where(
            'status',
            ReadingPlanStatus::InProgress
        )
            ->whereDate('target_date', Carbon::today())
            ->get();

        foreach ($plans as $readingPlan) {
            $readingPlan->user->notify(
                new ReadingPlanReminderNotification($readingPlan, 'today')
            );
        }

        return self::SUCCESS;
    }
}
