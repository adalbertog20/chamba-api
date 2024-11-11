<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Chamba\StoreChambaRequest;
use App\Models\Chamba;
use App\Models\Job;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->leftJoin('images as image', 'c.image_id', '=', 'image.id')
            ->select('c.*', 'worker.name as worker_name', 'job.name as job_name', 'image.path', 'worker.slug as worker_slug')
            ->get();

        return response()->json($chambas);
    }

    public function mostRatedChambas()
    {
        $mostRated = DB::table('chambas as c')
            ->join('users as worker', 'c.worker_id', '=', 'worker.id')
            ->join('jobs as job', 'c.job_id', '=', 'job.id')
            ->leftJoin('images as image', 'c.image_id', '=', 'image.id')
            ->select('c.*', 'worker.name as worker_name', 'job.name as job_name', 'image.path', 'worker.slug as worker_slug')
            ->orderBy('c.rating', 'desc')
            ->limit(4)
            ->get();

        return response()->json($mostRated);
    }

    public function myChambas()
    {
        $chambas = DB::table('chambas')
            ->leftJoin('images', 'chambas.image_id', '=', 'images.id')
            ->where('worker_id', Auth::user()->id)
            ->select('chambas.*', 'images.path')
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
            ->leftJoin('images', 'chambas.image_id', '=', 'images.id')
            ->select('chambas.*', 'jobs.name as job_name', 'users.name as worker_name', 'images.path')
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
                'worker_id' => auth()->user()->id,
                'image_id' => $validatedData['image_id']
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

    public function update(Request $request, $id)
    {
        $chamba = Chamba::where('id', $id)->firstOrFail();

        $validatedData = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
            'job_id' => ['string', 'exists:jobs,id']
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                "message" => "Validation Failed",
                "errors" => $validatedData->errors()
            ], 422);
        }

        $chamba->fill([
            'title' => $request->title,
            'description' => $request->description,
            'job_id' => $request->job_id
        ]);

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

    public function showName($id)
    {
        $chamba = Chamba::findOrFail($id);
        return response()->json([
            'name' => $chamba->title
        ]);
    }

    public function getChambasBySlug($slug)
    {
        $chambas = DB::table('chambas as c')
            ->join('users as worker', 'c.worker_id', '=', 'worker.id')
            ->join('jobs as job', 'c.job_id', '=', 'job.id')
            ->leftJoin('images as image', 'c.image_id', '=', 'image.id')
            ->select('c.*', 'worker.name as worker_name', 'job.name as job_name', 'image.path', 'worker.slug as worker_slug')
            ->where('job.slug', $slug)
            ->get();

        return response()->json([
            'chambas' => $chambas
        ]);
    }

    public function GetUserFollowingChambas()
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        if (Auth::user()->following->isEmpty()) {
            return response()->json([
                'message' => 'No Chambas Found, Follow Some Workers'
            ]);
        }

        $chambas = DB::table('chambas as c')
            ->join('user_follows as uf', 'c.worker_id', '=', 'uf.followed_id')
            ->join('users as worker', 'c.worker_id', '=', 'worker.id')
            ->join('jobs as job', 'c.job_id', '=', 'job.id')
            ->leftJoin('images as image', 'c.image_id', '=', 'image.id')
            ->select('c.*', 'worker.name as worker_name', 'job.name as job_name', 'image.path', 'worker.slug as worker_slug')
            ->where('uf.follower_id', Auth::id())
            ->get();

        return response()->json($chambas);
    }
}
