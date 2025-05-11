<?php

namespace App\Helpers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskNotificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    public static function notifyUser(User $user, string $message, int|null $taskId = null): void
    {
        Notification::create([
            'user_id' => $user->id,
            'task_id' => $taskId,
            'message' => $message,
            'seen' => false,
        ]);

        try {
            if (!empty($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($user->email)->send(new TaskNotificationMail($message));
            }
        } catch (\Exception $e) {
            Log::error("Erro ao enviar e-mail para {$user->email}: " . $e->getMessage());
        }
    }
}
