<?php

namespace Database\Seeders;

use App\Enums\ReadingPlanStatus;
use App\Models\ReadingPlan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReadingPlanSeeder extends Seeder
{
    public function run(): void
    {
        $readingPlans = [
            [
                'user_id' => 1,
                'book_id' => 1,
                'target_date' => Carbon::today()->addDays(7),
                'status' => ReadingPlanStatus::InProgress,
                'completed_at' => null,
            ],

            [
                'user_id' => 1,
                'book_id' => 2,
                'target_date' => Carbon::today(),
                'status' => ReadingPlanStatus::InProgress,
                'completed_at' => null,
            ],

            [
                'user_id' => 1,
                'book_id' => 3,
                'target_date' => Carbon::today()->addDays(3),
                'status' => ReadingPlanStatus::InProgress,
                'completed_at' => null,
            ],

            [
                'user_id' => 1,
                'book_id' => 4,
                'target_date' => Carbon::today()->subDays(2),
                'status' => ReadingPlanStatus::InProgress,
                'completed_at' => null,
            ],

            [
                'user_id' => 1,
                'book_id' => 5,
                'target_date' => Carbon::today()->subDays(5),
                'status' => ReadingPlanStatus::Expired,
                'completed_at' => null,
            ],

            [
                'user_id' => 1,
                'book_id' => 6,
                'target_date' => Carbon::today()->subDays(1),
                'status' => ReadingPlanStatus::Completed,
                'completed_at' => Carbon::today()->subDays(1),
            ],

            [
                'user_id' => 2,
                'book_id' => 7,
                'target_date' => Carbon::today()->addDays(5),
                'status' => ReadingPlanStatus::InProgress,
                'completed_at' => null,
            ],
        ];

        foreach ($readingPlans as $readingPlan) {
            ReadingPlan::create($readingPlan);
        }
    }
}
