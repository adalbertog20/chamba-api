<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request, $id)
    {
        $message = Message::create([
            'body' => $request->body,
            'user_id' => Auth::user()->id,
            'room_id' => $id
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message->load('user');
    }

    public function fetchMessage($id)
    {
        $messages = Message::join('users', 'messages.user_id', '=', 'users.id')
            ->where('room_id', $id)
            ->get(['messages.*', 'users.name as user_name']);

        return response()->json($messages);
    }
}
