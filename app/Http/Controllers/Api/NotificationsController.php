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
}
