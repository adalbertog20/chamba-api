<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage())
                ->greeting('Hola!, Bienvenido a Chamba ' . $notifiable->name)
                ->subject('Verifica tu dirección de correo electrónico')
                ->line('Por favor, haga clic en el botón de abajo para verificar su dirección de correo electrónico.')
                ->action('Verificar', $url);
        });
    }
}
