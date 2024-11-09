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

    public function store($uuid)
    {
        $chat = Chat::findOrFail($uuid);

        if (Auth::id() !== $chat->client_id && Auth::id() !== $chat->worker_id) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        $validatedData = request()->validate([
            'body' => ['required', 'string'],
        ]);

        $message = \App\Models\Message::create([
            'body' => $validatedData['body'],
            'user_id' => Auth::id(),
            'chat_id' => $uuid,
        ]);

        return response()->json([
            'message' => 'Message sent',
        ]);
    }
}
