<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chamba;
use App\Models\Image;
use App\Models\RequestChamba;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $chambas = Chamba::with(['user'])->where('worker_id', Auth::id())->count();
        $requests = RequestChamba::with(['worker'])->where('worker_id', Auth::id())->where('status', 'pending')->count();
        $images = Image::where('user_id', Auth::id())->count();
        $chambas_realizadas = RequestChamba::where('worker_id', Auth::id())->where('status', 'completed')->count();

        return response()->json([
            'chambas' => $chambas,
            'requests' => $requests,
            'images' => $images,
            'chambas_realizadas' => $chambas_realizadas,
        ]);
    }
}
