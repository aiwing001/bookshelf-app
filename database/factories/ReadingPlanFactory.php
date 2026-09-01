<?php

namespace Database\Factories;

use App\Enums\ReadingPlanStatus;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'target_date' => fake()->dateTimeBetween('today', '+30 days'),
            'status' => ReadingPlanStatus::InProgress,
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => ReadingPlanStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => ReadingPlanStatus::Expired,
        ]);
    }
}