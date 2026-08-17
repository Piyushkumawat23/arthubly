<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// Yahan hume MailMessage ki zaroorat nahi hai, isliye hata diya.

class AdminAlertNotification extends Notification
{
    use Queueable;

    public $alertData;

    /**
     * Data controller se aayega ($alertData me)
     */
    public function __construct($alertData)
    {
        $this->alertData = $alertData;
    }

    /**
     * Yahan 'database' return karna zaroori hai.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Database me jo data save hoga, wo yahan se jayega.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->alertData['type'],
            'message' => $this->alertData['message'],
            'url' => $this->alertData['url'] ?? '#'
        ];
    }
}