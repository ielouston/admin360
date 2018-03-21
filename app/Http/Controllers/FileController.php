<?php

namespace Admin360\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function upload($folder, Request $req){
        
        $file = $req->file('file');
        $name_source = $file->getClientOriginalName();
        $name_dest = md5(time().$name_source);

        $path_full = 'avatars/full/' . $name_dest; 
        $mime = $file->getClientMimeType();

        if($mime != "application/octet-stream"){
            return response()->json(0, 400);
        }
        Storage::disk('local')->put('public/' . $path_full, (string)$image->encode());    

        $image->fit(250, 250, function ($constraint) {
            $constraint->aspectRatio();
        });
        
        $path_thumb = 'avatars/thumb/' . $name_dest;

        Storage::disk('local')->put('public/' . $path_thumb, (string)$image->encode());    
        
        // Transform $upload using something like Fractal
        return response()->json($name_dest, 200);
    }
    public function download(){

    }
    // public function serveAvatar($profile){
    //     $storagePath = storage_path() . '/avatar/' . $profile . ''; 

    //     return Image::make($storagePath)->response();
    // }
}
