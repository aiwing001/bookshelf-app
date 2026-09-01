<?php

namespace App\Notifications;

use App\Models\ReadingPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReadingPlanReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        private ReadingPlan $readingPlan,
        private string $type,
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'book_id' => $this->readingPlan->book_id,
            'title' => $this->readingPlan->book->title,
            'body' => $this->type === '3days'
                ? '目標日まであと3日です'
                : '今日が目標日です',
            'timing' => $this->type === '3days'
                ? 'three_days_before'
                : 'on_due_date',
            'target_date' => $this->readingPlan->target_date,
        ];
    }
}
