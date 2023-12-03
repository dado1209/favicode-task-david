<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FileRequest;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index() {
        // Load all the users files
        $files = User::find(Auth::id())->files;
        return view('dashboard')->with('files', $files);
    }
    public function upload(FileRequest $req) {
        try {
        // Upload the file to userId folder
        $userid = Auth::id();
        $file = $req->file('file');
        // TODO: check if user has enough storage
        // Get filename once it is stored on server
        $storedName = basename($file->store($userid));
        // Create entry in database
        $newFile = new File();
        $newFile->user_id = auth()->id();
        $newFile->name = $file->getClientOriginalName();
        $newFile->size = $file->getSize();
        $newFile->type = $req->input('type');
        $newFile->storedName = $storedName;
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

    public function download(Request $req){
        $userId = Auth::id();
        $file = File::find($req->id);
        // Check if user has permission to download file
        if($file->type == 'private' && $file->user_id != $userId) {
            return response()->json(['error' => 'You do not have permission to download this file'], 403);
        }

        $filePath = "{$userId}/{$file->storedName}";
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        // Download the file
        return response()->download(Storage::path($filePath));
    }
}
