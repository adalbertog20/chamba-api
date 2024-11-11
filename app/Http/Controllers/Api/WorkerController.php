<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = DB::table('users as u')
            ->where('u.role', '1')
            ->select('u.*')
            ->get();
        return response()->json($workers);
    }
}
