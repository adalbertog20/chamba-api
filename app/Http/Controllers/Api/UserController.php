<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        if (Auth::attempt($request->only('email', 'password'))) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;
            return response()->json([
                'message' => 'User create and logged in',
                'user' => $user,
                'token' => $token
            ], 201);
        }

        return response()->json([
            'message' => 'User created',
            'user' => $user,
        ], 201);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'User Updated Successfully',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user->update([
            'password' => Hash::make($validatedData['password'])
        ]);

        return response()->json([
            'message' => 'Password Updated Successfully',
            'user' => $user
        ]);
    }

    public function updateJobs(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'jobs_ids' => ['required', 'array', 'min:1'],
            'jobs_ids.*' => ['required', 'integer', 'exists:jobs,id']
        ]);

        $user->jobs()->sync($request->jobs_ids);

        return response()->json([
            'message' => 'Jobs Updated Successfully',
        ]);
    }

    public function showJobs(Request $request)
    {
        $userId = $request->user()->id;
        $user = User::find($userId);
        $jobs = $user->jobs;
        return response()->json([
            'jobs' => $jobs
        ]);
    }
}
