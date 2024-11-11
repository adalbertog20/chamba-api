<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chamba;
use App\Models\Image;
use App\Models\RequestChamba;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $client_ids = RequestChamba::where('worker_id', Auth::id())->where('status', 'done')->get()->pluck('client_id');

        $chambas = Chamba::with(['user'])->where('worker_id', Auth::id())->count();
        $requests = RequestChamba::with(['worker'])->where('worker_id', Auth::id())->where('status', 'pending')->count();
        $images = Image::where('user_id', Auth::id())->count();
        $chambas_realizadas = RequestChamba::where('worker_id', Auth::id())->where('status', 'done')->count();
        $clients_city = User::whereIn('id', $client_ids)->pluck('city');
        $city_counts = [
            'La Paz' => $clients_city->filter(fn($city) => $city === 'La Paz')->count(),
            'San Jose del Cabo' => $clients_city->filter(fn($city) => $city === 'San Jose del Cabo')->count(),

        ];

        $chambas_most_requested = RequestChamba::select('chambas.title', DB::raw('count(request_chambas.id) as conteo'))
            ->join('chambas', 'chambas.id', '=', 'request_chambas.chamba_id')
            ->where('request_chambas.worker_id', Auth::id())
            ->groupBy('chambas.title')
            ->orderBy('conteo', 'desc')
            ->limit(5)
            ->get();


        return response()->json([
            'chambas_count' => $chambas,
            'requests_pendant_count' => $requests,
            'images_count' => $images,
            'chambas_done_count' => $chambas_realizadas,
            'clients_city_count' => $city_counts,
            'chambas_most_requested_count' => $chambas_most_requested
        ]);
    }
}
