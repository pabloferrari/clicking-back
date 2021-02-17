<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use File;

class ImageController extends Controller
{
    //
    public function index($img) {

        $headers = [
            'Content-Type' => 'application/jpeg'
        ];
        $file = storage_path('app/public/') . $img;
        return response()->file($file, $headers);

    }


}
