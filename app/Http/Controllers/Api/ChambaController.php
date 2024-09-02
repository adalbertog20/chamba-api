<?php

namespace App\Http\Controllers\Api;

use App\Models\Chamba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChambaController extends Controller
{
    public function index()
    {
        return response()->json([
            'chambas' => Chamba::all()
        ]);
    }

    public function show($id)
    {
        $chamba = DB::table('chambas')
            ->join('jobs', 'chambas.job_id', '=', 'jobs.id')
            ->join('users', 'chambas.worker_id', '=', 'users.id')
            ->select('chambas.*', 'jobs.name as job_name', 'users.name as worker_name')
            ->where('chambas.id', $id)
            ->first();

        return response()->json([
            "chamba" => $chamba
        ]);
    }

    public function store(Request $request)
    {
        if(Gate::allows('isWorker')) {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'job_id' => ['required', 'string', 'exists:jobs,id'],
                'worker_id' => ['required', 'integer', 'exists:users,id'],
            ]);

            $chamba = Chamba::create($request->all());

            return response()->json([
                "message" => "Chamba Created Successfully",
                "chamba" => $chamba
            ]);
        } else {
            return response()->json([
                'error' => "Error"
            ], 401);
        }
    }

    public function update(Request $request, $id, Chamba $chamba)
    {
        $chamba = Chamba::where('id', $id)->firstOrFail();

        $validatedData = $request->validate([
            'title' => ['string', 'max:255'],
            'description' => ['string'],
            'job_id' => ['string', 'exists:jobs,id'],
            'worker_id' => ['integer', 'exists:users,id'],
        ]);

        $chamba->title = $validatedData['title'] ?? $chamba->title;
        $chamba->description = $validatedData['description'] ?? $chamba->description;
        $chamba->job_id = $validatedData['job_id'] ?? $chamba->job_id;
        $chamba->worker_id = $validatedData['worker_id'] ?? $chamba->worker_id;

        $this->authorize('update', $chamba);
        $chamba->save();

        return response()->json([
            "message" => "Chamba Updated Successfully",
            "chamba" => $chamba
        ]);
    }

    public function destroy($id, Chamba $chamba)
    {
        $chamba = Chamba::find($id);
        $this->authorize('destroy', $chamba);
        $chamba->delete();

        return response()->json([
            "message" => "Chamba Deleted Successfully"
        ]);
    }
}
