<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestChamba;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RequestChambaController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        $requests = RequestChamba::all()->where('worker_id', $userId);

        return response()->json([
            'message' => 'All Requests',
            'requests' => $requests
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'worker_id' => ['required', 'integer', 'exists:users,id'],
            'chamba_id' => ['required', 'integer', 'exists:chambas,id'],
            'message' => ['required', 'string'],
        ]);

        $request = RequestChamba::create([
            'client_id' => auth()->user()->id,
            'worker_id' => $validatedData['worker_id'],
            'chamba_id' => $validatedData['chamba_id'],
            'message' => $validatedData['message'],
        ]);

        return response()->json([
            'message' => 'Request created',
            'request' => $request
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $requestChamba = RequestChamba::find($id);

        $validatedData = $request->validate([
            'status' => ['required', 'string', 'in:accepted,rejected']
        ]);

        $requestChamba->update([
            'status' => $validatedData['status']
        ]);

        return response()->json([
            'message' => 'Request Status Updated',
            'request' => $requestChamba
        ]);
    }
}
