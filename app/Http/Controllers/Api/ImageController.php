<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\StoreImageRequest;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function index() {
        $user_images = Image::where('user_id', Auth::id())->get();
        return response()->json([
            "images" => $user_images
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'alt' => ['nullable', 'string']
        ]);

        $request->user_id = Auth::id();

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('gallery'), $imageName);
        $image = new Image();
        $image->user_id = $request->user_id;
        $image->image = $imageName;
        $image->alt = $request->alt;
        $image->path = asset('gallery/' . $imageName);
        $image->save();

        return response()->json([
            'message' => 'Image uploaded',
        ]);
    }
    public function destroy($id) {
        $image = Image::find($id);
        if ($image->user_id == Auth::id()) {
            $image->delete();
            return response()->json([
                'message' => 'Image deleted'
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
