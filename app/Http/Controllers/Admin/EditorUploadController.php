<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Endpoint unggah gambar untuk editor TinyMCE.
 */
class EditorUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);

        $path = $request->file('file')->store('uploads/' . now()->format('Y/m'), 'public');

        return response()->json(['location' => media_url($path)]);
    }
}
