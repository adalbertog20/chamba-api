<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function store(Request $request)
    {
        $client = User::find($request->client_id);
        $room = Room::firstOrCreate([
            'client_id' => $request->client_id,
            'worker_id' => $request->worker_id,
        ]);
        return response()->json([
            'client' => $client, 'id' => $request->client_id, 'room_id' => $room->id
        ], 200);
    }
}
