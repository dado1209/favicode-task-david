<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FileRequest;
use App\Models\File;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function index(FileRequest $req) {
        try {
        // Upload the file to userId folder
        $userid = Auth::id();
        $file = $req->file('file');
        // TODO: check if user has enough storage
        $path = $file->store($userid);
        // Create entry in database
        $newFile = new File();
        $newFile->user_id = auth()->id();
        $newFile->name = $file->getClientOriginalName();
        $newFile->size = $file->getSize();
        $newFile->type = $req->input('type');
        $newFile->save();
        return redirect()->route('/')->with('success', 'File uploaded successfully!');
        }
        catch(\Exception $e) {
             // Log the exception for debugging purposes
             Log::error('Exception during file upload: ' . $e->getMessage());

             // Return a user-friendly error message
             return redirect()->route('showUpload')->with('error', 'An error occurred during file upload.');
        }

    }

    public function showUploadForm() {
        return view('upload');
    }
}
