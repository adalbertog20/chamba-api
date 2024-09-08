<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'min:10', 'max:12', 'unique:users,phone_number,' . $userId],
            'street' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'min:5'],
            'city' => ['required', 'string', 'max:255', 'in:La Paz,San Jose del Cabo']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->fill([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'street' => $request->street,
            'postal_code' => $request->postal_code,
            'city' => $request->city
        ]);

        $user->save();

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
