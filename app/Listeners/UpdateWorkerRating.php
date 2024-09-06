<?php

namespace App\Listeners;

use App\Models\User;

class UpdateWorkerRating
{

    public function handle(object $event): void
    {
        $review = $event->review;
        $worker = User::find($review->worker_id);
        $numberOfReviews = $worker->reviews->count();
        $worker->rating = ($worker->rating * ($numberOfReviews - 1) + $review->rating) / $numberOfReviews;
        $worker->save();
    }
}
