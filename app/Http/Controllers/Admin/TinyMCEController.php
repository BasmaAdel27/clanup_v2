<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TinyMCEController extends Controller
{
    /**
     * Upload image for tinymce.
     */
    public function upload(Request $request)
    {
        $file_name = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('tinymce', $file_name, 'public_dir');
        
        return response()->json(['location' => "/uploads/$path"]); 
    }
}