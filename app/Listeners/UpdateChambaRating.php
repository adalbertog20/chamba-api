<?php

namespace App\Listeners;

use App\Events\ReviewStored;
use App\Models\Chamba;

class UpdateChambaRating
{
    public function handle(ReviewStored $event): void
    {
        $review = $event->review;
        $chamba = Chamba::find($review->chamba_id);
        $numberOfReviews = $chamba->reviews->count();
        $chamba->rating = ($chamba->rating * ($numberOfReviews - 1) + $review->rating) / $numberOfReviews;
        $chamba->save();
    }
}
