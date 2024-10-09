<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $loginData = $request->validated();

        if (Auth::attempt($loginData)) {
            $user = Auth::user();
            $isWorker = $user->isWorker();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'token' => $token,
                'isWorker' => $isWorker
            ]);
        }
        return response()->json(['error', 'Unauthorized'], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
