<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function getFollowers($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers;
        return response()->json([
            'followers' => $followers,
            'count' => $followers->count(),
            'followed' => Auth::user()->following->contains($id)
        ]);
    }

    public function follow($id)
    {
        Auth::user()->following()->attach($id);
        return response()->json(['message' => 'Followed']);
    }

    public function unfollow($id)
    {
        Auth::user()->following()->detach($id);

        return response()->json(['message' => 'Unfollowed']);
    }
}
