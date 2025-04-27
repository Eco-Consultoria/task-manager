<?php

namespace App\Helpers;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationHelper
{
    public static function notifyUser(int $userId, string $message, int|null $taskId = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'message' => $message,
            'seen' => false,
        ]);
    }
}
