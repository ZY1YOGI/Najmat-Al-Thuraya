<?php

namespace App\Traits;

use Filament\Notifications\Notification;

trait HasNotifications
{

    public static function sendNotificationSuccess(string $title, string $body = ''): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
    }
}
