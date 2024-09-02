<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Chamba\StoreChambaRequest;
use App\Http\Requests\Chamba\UpdateChambaRequest;
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

    public function store(StoreChambaRequest $request)
    {
        if(Gate::allows('isWorker')) {
            $validatedData = $request->validated();

            $chamba = Chamba::create($validatedData);

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
}
