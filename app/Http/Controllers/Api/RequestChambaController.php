<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chamba;
use App\Models\Chat;
use App\Models\RequestChamba;
use App\Models\User;
use App\Notifications\RequestChambaStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RequestChambaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (Auth::user()->isWorker()) {
            $requests = DB::table('request_chambas as rc')
                ->join('users as client', 'rc.client_id', '=', 'client.id')
                ->join('users as worker', 'rc.worker_id', '=', 'worker.id')
                ->join('chambas', 'rc.chamba_id', '=', 'chambas.id')
                ->where('rc.worker_id', $userId)
                ->select('rc.*', 'client.name as client_name', 'worker.name as worker_name', 'chambas.title as chamba_name', 'chambas.slug as chamba_slug')
                ->get();
        } else {
            $requests = DB::table('request_chambas as rc')
                ->join('users as client', 'rc.client_id', '=', 'client.id')
                ->join('users as worker', 'rc.worker_id', '=', 'worker.id')
                ->join('chambas', 'rc.chamba_id', '=', 'chambas.id')
                ->where('rc.client_id', $userId)
                ->select('rc.*', 'client.name as client_name', 'worker.name as worker_name', 'chambas.title as chamba_name', 'chambas.slug as chamba_slug')
                ->get();
        }

        return response()->json([
            'requests' => $requests
        ]);
    }

    public function show($id)
    {
        $request = DB::table('request_chambas as rc')
            ->join('users as client', 'rc.client_id', '=', 'client.id')
            ->join('users as worker', 'rc.worker_id', '=', 'worker.id')
            ->join('chambas', 'rc.chamba_id', '=', 'chambas.id')
            ->join('chats', 'rc.id', '=', 'chats.request_chamba_id')
            ->where('rc.id', $id)
            ->select('rc.*', 'client.name as client_name', 'worker.name as worker_name', 'chambas.title as chamba_name', 'chambas.slug as chamba_slug', 'chats.uuid as chat_uuid')
            ->first();

        if (!$request) {
            return response()->json([
                'message' => 'Request not found'
            ], 404);
        }

        return response()->json($request);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'worker_id' => ['required', 'string', 'exists:users,id'],
            'chamba_id' => ['required', 'string', 'exists:chambas,id'],
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

        $client = User::where('id', $requestChamba->client_id)->firstOrFail();
        $worker = User::where('id', $requestChamba->worker_id)->firstOrFail();
        $chamba = Chamba::where('id', $requestChamba->chamba_id)->firstOrFail();

        if ($validatedData['status'] === 'accepted') {
            Chat::firstOrCreate([
                'client_id' => $client->id,
                'worker_id' => $worker->id,
                'request_chamba_id' => $requestChamba->id
            ]);
        }

        $client->notify(new RequestChambaStatusUpdated($requestChamba, $client, $worker, $chamba->title));

        return response()->json([
            'message' => 'Request Status Updated',
            'request' => $requestChamba
        ]);
    }

    public function startChamba($id)
    {
        $requestChamba = RequestChamba::find($id);

        $requestChamba->update([
            'status' => 'started',
            'start_date' => now()
        ]);

        return response()->json([
            'message' => 'Chamba Started',
            'request' => $requestChamba
        ]);
    }

    public function endChamba($id)
    {
        $requestChamba = RequestChamba::find($id);

        $requestChamba->update([
            'status' => 'done',
            'end_date' => now()
        ]);

        return response()->json([
            'message' => 'Chamba Ended',
            'request' => $requestChamba
        ]);
    }
}
