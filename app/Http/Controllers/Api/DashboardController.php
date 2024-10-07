<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chamba;
use App\Models\RequestChamba;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $chambas = Chamba::with(['user'])->where('worker_id', Auth::id())->count();
        $requests = RequestChamba::with(['worker'])->where('worker_id', Auth::id())->where('status', 'pending')->count();
        return response()->json([
            'chambas' => $chambas,
            'requests' => $requests
        ]);
    }
}
