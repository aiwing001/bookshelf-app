<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;

class UpdateExpiredReadingPlans extends Command
{
    protected $signature = 'reading-plans:update-expired';

    protected $description = 'Update expired reading plans';

    public function handle()
    {
        ReadingPlan::query()
            ->where('status', ReadingPlanStatus::InProgress)
            ->whereDate('target_date', '<', today())
            ->update([
                'status' => ReadingPlanStatus::Expired,
            ]);

        return self::SUCCESS;
    }
}
