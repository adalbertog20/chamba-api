<?php

namespace App\Notifications;

use App\Models\Chamba;
use App\Models\RequestChamba;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class RequestChambaStatusUpdated extends Notification
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

    public function toArray(object $notifiable): array
    {
        $estado = match ($this->requestChamba->status) {
            'accepted' => 'Aceptada',
            'rejected' => 'Rechazada',
        };

        $estadoVerbo = match ($this->requestChamba->status) {
            'accepted' => 'Aceptar',
            'rejected' => 'Rechazar',
        };

        return [
            'title' => 'Solicitud de ' . $this->chambaTitle . ' a sido ' . $estado . '.',
            'msg' => 'El trabajador ' . $this->worker->name . ' a decidido ' . $estadoVerbo . ' tu chamba.'
        ];
    }
}
