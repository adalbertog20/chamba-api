<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\StoreImageRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
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
        $image->save();

        return response()->json([
            'message' => 'Image uploaded',
        ]);
    }
}
