<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index() {
        return response()->json([
            'notifications' => Auth::user()->notifications,
        ]);
    }
    public function markAsRead($id) {
        Auth::user()->notifications->where('id', $id)->markAsRead();
        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllAsRead() {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}
