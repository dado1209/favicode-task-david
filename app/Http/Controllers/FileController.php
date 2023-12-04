<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FileController extends Controller
{
    public function index() {
        //TODO: get all file names of userId folder and get all files from files tables using the file names
        //it is possible that the userFiles folder will be deleted
        // Load all the users files
        $files = User::find(Auth::id())->files;
        return view('dashboard')->with('files', $files);
    }
    public function upload(FileRequest $req) {
        try {
        $userId = Auth::id();
        $file = $req->file('file');
        $type = $req->input('type');
        // Upload the file to userId folder
        FileService::upload($userId,$file,$type);
        return redirect()->route('index')->with('success', 'File uploaded successfully!');
        }
        catch(\Exception $e) {
             // Log the exception for debugging purposes
             Log::error('Exception: ' . $e->getMessage());
             // Return a user-friendly error message
             return redirect()->route('showUpload')->withErrors(['message' => 'Something went wrong']);
        }

    }

    public function showUploadForm() {
        return view('upload');
    }

    public function download(Request $req){
        try{
            $userId = Auth::id();
            // Find file in files table
            $file = FileService::getFile($req->id);
            return response()->download(FileService::download($userId, $file));
        }
        catch(AuthenticationException $e) {
            Log::error('AuthenticationException: ' . $e->getMessage());
            session()->flash('Error', 'You do not have permission to download this file');
            return redirect()->back();
        }
        catch(ModelNotFoundException $e) {
            Log::error('ModelNotFoundException: ' . $e->getMessage());
            session()->flash('Error', 'File not found');
            return redirect()->back();
        }
        catch(FileNotFoundException $e) {
            Log::error('FileNotFoundException: ' . $e->getMessage());
            session()->flash('Error', 'File not found');
            return redirect()->back();
        }
        catch(\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            session()->flash('Error', 'Something went wrong');
            return redirect()->back();
        }
    }
}
