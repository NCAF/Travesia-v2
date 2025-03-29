<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function uploadImage($file){
        // Check if file exists
        if (!$file) {
            return null;
        }
        
        $imageName = time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('images'), $imageName);
        return $imageName;
    }

    public function viewImage($imageName){
        return view('image', ['imageName' => $imageName]);
    }
}
