<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\AuthService;
use App\Services\FileService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\StorageLimitExceededException;

class FileController extends Controller
{
    public function index()
    {
        try {
            return view('dashboard')->with(['files' => FileService::getUserFiles(Auth::id()), 'user' => Auth::user()]);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return view('errors.error')->with(['message' => 'Something went wrong']);
        }
    }
    public function upload(FileRequest $req)
    {
        try {
            $userId = Auth::id();
            $file = $req->file('file');
            $type = $req->input('type');
            // Upload the file to userId folder
            FileService::upload($userId, $file, $type);
            return redirect()->route('index')->with('success', 'File uploaded successfully!');
        } catch (StorageLimitExceededException $e) {
            Log::error('Exception: ' . $e->getMessage());
            // Return a user-friendly error message
            return redirect()->route('showUpload')->withErrors(['message' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Exception: ' . $e->getMessage());
            // Return a user-friendly error message
            return redirect()->route('showUpload')->withErrors(['message' => 'Something went wrong']);
        }
    }

    public function showUploadForm()
    {
        try {
            return view('upload')->with(['files' => FileService::getUserFiles(Auth::id()), 'user' => Auth::user()]);
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return view('errors.error')->with(['message' => 'Something went wrong']);
        }
    }

    public function download(Request $req)
    {
        try {
            $userId = Auth::id();
            // Find file in files table
            $file = FileService::getFile($req->id);
            return response()->download(FileService::getFileFromDirectory($userId, $file));
        } catch (AuthenticationException $e) {
            Log::error('AuthenticationException: ' . $e->getMessage());
            session()->flash('Error', 'You do not have permission to download this file');
            return redirect()->route('index');
        } catch (ModelNotFoundException $e) {
            Log::error('ModelNotFoundException: ' . $e->getMessage());
            session()->flash('Error', 'File not found');
            return redirect()->route('index');
        } catch (FileNotFoundException $e) {
            Log::error('FileNotFoundException: ' . $e->getMessage());
            session()->flash('Error', 'File not found');
            return redirect()->route('index');
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            session()->flash('Error', 'Something went wrong');
            return redirect()->route('index');
        }
    }

    public function update(Request $req)
    {
        try {
            FileService::updateFile($req->id, $req->input('newFileName'), $req->input('newFileType'));
            session()->flash('Update', 'File has been updated');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            session()->flash('Error', 'Something went wrong');
            return redirect()->route('index');
        }
    }

    public function delete(Request $req)
    {
        try {
            FileService::deleteFile(Auth::id(), $req->id);
            session()->flash('Update', 'File has been deleted');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Exception: ' . $e->getMessage());
            return view('errors.error')->with(['message' => 'Something went wrong']);
        }
    }
}
