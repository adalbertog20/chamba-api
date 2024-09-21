<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Chamba\StoreChambaRequest;
use App\Http\Requests\Chamba\UpdateChambaRequest;
use App\Models\Chamba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ChambaController extends Controller
{
    public function index()
    {
        $chambas = DB::table('chambas as c')
            ->join('users as worker', 'c.worker_id', '=', 'worker.id')
            ->join('jobs as job', 'c.job_id', '=', 'job.id')
            ->select('c.*', 'worker.name as worker_name', 'job.name as job_name')
            ->get();

        return response()->json([
            'chambas' => $chambas
        ]);
    }

    public function show($slug)
    {
        $chamba = DB::table('chambas')
            ->join('jobs', 'chambas.job_id', '=', 'jobs.id')
            ->join('users', 'chambas.worker_id', '=', 'users.id')
            ->select('chambas.*', 'jobs.name as job_name', 'users.name as worker_name')
            ->where('chambas.slug', $slug)
            ->first();

        return response()->json([
            "chamba" => $chamba
        ]);
    }

    public function store(StoreChambaRequest $request)
    {
        if (Gate::allows('isWorker')) {
            $validatedData = $request->validated();


            $chamba = Chamba::create([
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title']),
                'description' => $validatedData['description'],
                'job_id' => $validatedData['job_id'],
                'worker_id' => auth()->user()->id
            ]);

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

    public function update(UpdateChambaRequest $request, $id, Chamba $chamba)
    {
        $chamba = Chamba::where('id', $id)->firstOrFail();

        $validatedData = $request->validated();

        $this->authorize('update', $chamba);
        $chamba->update($validatedData);

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

    public function showName($id)
    {
        $chamba = Chamba::findOrFail($id);
        return response()->json([
            'name' => $chamba->title
        ]);
    }
}
