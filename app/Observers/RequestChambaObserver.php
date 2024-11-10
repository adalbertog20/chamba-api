<?php

namespace App\Observers;

use App\Models\RequestChamba;
use App\Notifications\RcEndDateNotification;
use App\Notifications\RcStartDateNotification;

class RequestChambaObserver
{
    /**
     * Handle the RequestChamba "created" event.
     */
    public function created(RequestChamba $requestChamba): void
    {
        //
    }

    /**
     * Handle the RequestChamba "updated" event.
     */
    public function updated(RequestChamba $requestChamba): void
    {
        $client = $requestChamba->client;
        if ($requestChamba->isDirty('start_date')) {
            $client->notify(new RcStartDateNotification($requestChamba, $client, $requestChamba->worker, $requestChamba->chamba->title));
        }
        if ($requestChamba->isDirty('end_date')) {
            $client->notify(new RcEndDateNotification($requestChamba, $client, $requestChamba->worker, $requestChamba->chamba->title));
        }
    }

    /**
     * Handle the RequestChamba "deleted" event.
     */
    public function deleted(RequestChamba $requestChamba): void
    {
        //
    }

    /**
     * Handle the RequestChamba "restored" event.
     */
    public function restored(RequestChamba $requestChamba): void
    {
        //
    }

    /**
     * Handle the RequestChamba "force deleted" event.
     */
    public function forceDeleted(RequestChamba $requestChamba): void
    {
        //
    }
}
