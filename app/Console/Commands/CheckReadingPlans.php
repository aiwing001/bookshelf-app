<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use App\Notifications\ReadingPlanReminderNotification;
use Carbon\Carbon;

class CheckReadingPlans extends Command
{
    protected $signature = 'app:check-reading-plans';

    protected $description = 'Command description';

    public function handle()
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
    }
}
