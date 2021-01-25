<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function index(){
        $files = File::all();
        return view('upload.index', compact('files'));
    }
    public function upload(Request $request){

        $fileModel = new File;
            $fileName = time().'_'.$request->import->getClientOriginalName();
            $filePath = $request->file('import')->storeAs('uploads', $fileName, 'public');
            $fileModel->type = $request->type;
            $fileModel->user_id = Auth::user()->id;
            $fileModel->name = $request->name;
            $fileModel->path = $filePath;
            $fileModel->save();

        return back()->with(['success' => 'Your File was uploaded successfully!']);
    }
}
