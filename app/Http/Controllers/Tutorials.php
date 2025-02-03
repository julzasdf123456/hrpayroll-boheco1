<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Flash;

class Tutorials extends AppBaseController {
    public function index(Request $request) {
        return view('/tutorials/index');
    }

    public function videoViewer($fileName, $title, $description) {

        return view('/tutorials/video_viewer', [
            'fileName' => $fileName,
            'title' => $title,
            'description' => $description,
        ]);
    }
}