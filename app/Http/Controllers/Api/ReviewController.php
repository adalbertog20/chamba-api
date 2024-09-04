<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index($id)
    {
        $reviews = Review::where('chamba_id', $id)->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }

    public function store(StoreReviewRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['client_id'] = auth()->user()->id;
        $review = Review::create($validatedData);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review
        ], 201);
    }
}
