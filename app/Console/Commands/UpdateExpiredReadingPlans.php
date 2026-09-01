<?php

namespace App\Console\Commands;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use Illuminate\Console\Command;

class UpdateExpiredReadingPlans extends Command
{
    protected $signature = 'reading-plans:update-expired';

    protected $description = '期限切れの読書計画を更新する';

    /**
     * 期限切れの読書計画をExpiredへ更新する。
     */
    public function handle(): int
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
