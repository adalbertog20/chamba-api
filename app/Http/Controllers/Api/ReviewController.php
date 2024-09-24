<?php

namespace App\Http\Controllers\Api;

use App\Events\ReviewStored;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index($id)
    {
        //$reviews = Review::where('chamba_id', $id)->get();
        $reviews = DB::table('reviews')
            ->join('users', 'reviews.client_id', '=', 'users.id')
            ->select('reviews.*', 'users.name as client_name')
            ->where('chamba_id', $id)->get();

        return response()->json([
            'reviews' => $reviews
        ]);
    }

    public function store(StoreReviewRequest $request)
    {
        $validatedData = $request->validated();
        $rc = DB::table('request_chambas')->where('id', $validatedData['request_chamba_id'])->first();
        $validatedData['client_id'] = auth()->user()->id;
        $validatedData['chamba_id'] = $rc->chamba_id;
        $validatedData['worker_id'] = $rc->worker_id;
        $review = Review::create($validatedData);

        ReviewStored::dispatch($review);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review
        ], 201);
    }
}
