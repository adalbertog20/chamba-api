<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($uuid)
    {
        $chat = Chat::all()->where('uuid', $uuid)->firstOrFail();

        if (Auth::id() !== $chat->client_id && Auth::id() !== $chat->worker_id) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        $messages = $chat->messages;

        return response()->json($messages);
    }

    public function store(Request $request, $uuid)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        $chat = Chat::where('uuid', $uuid)->firstOrFail();

        if (Auth::id() !== $chat->client_id && Auth::id() !== $chat->worker_id) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        $message = new \App\Models\Message();
        $message->body = $request->body;
        $message->user_id = Auth::id();
        $message->chat_id = $chat->id;
        $message->save();

        return response()->json($message);
    }
}
