<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Auth::user()->chats;
        if ($chats->isEmpty()) {
            return response()->json([
                'message' => 'No chats found'
            ]);
        }

        return response()->json($chats);
    }
}
