<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            //password_confirmation
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response()->json([
            'message' => 'User created',
            'user' => $user,
        ], 201);
    }
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:12', Rule::unique('users')->ignore($user->id)],
            'street' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'min:5'],
            'city' => ['required', 'string', 'max:255', 'in:La Paz, San Jose del Cabo']
        ]);

        $user->update($request->all());

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
