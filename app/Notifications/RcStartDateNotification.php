<?php

namespace App\Notifications;

use App\Models\RequestChamba;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RcStartDateNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(RequestChamba $requestChamba, User $client, User $worker, $chambaTitle)
    {
        $this->requestChamba = $requestChamba;
        $this->client = $client;
        $this->worker = $worker;
        $this->chambaTitle = $chambaTitle;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Chamba iniciada',
            'msg' => $this->worker->name . ' ha iniciado la chamba ' . $this->chambaTitle
        ];
    }
}
