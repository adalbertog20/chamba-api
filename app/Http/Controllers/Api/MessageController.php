<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index($id)
    {
        $chat = Chat::findOrFail($id);
        $messages = $chat->messages;

        if ($messages->isEmpty()) {
            return response()->json([
                'message' => 'No messages found'
            ]);
        }

        return response()->json($messages);
    }

    public function store($id)
    {
        $validatedData = request()->validate([
            'body' => ['required', 'string'],
        ]);

        $message = \App\Models\Message::create([
            'body' => $validatedData['body'],
            'user_id' => Auth::id(),
            'chat_id' => $id,
        ]);

        return response()->json([
            'message' => 'Message sent',
        ]);
    }
}
