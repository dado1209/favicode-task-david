<?php

namespace App\Services;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FileService {
    public static function upload($userId, $file, $type) {
        // TODO: check if user has enough storage
        // Get filename once it is stored on server
        $storedName = basename($file->store($userId));
        // Create entry in database
        $newFile = new File();
        $newFile->user_id = auth()->id();
        $newFile->name = $file->getClientOriginalName();
        $newFile->size = $file->getSize();
        $newFile->type = $type;
        $newFile->storedName = $storedName;
        $newFile->save();

    }

    public static function download($userId, $file){
        // Check if user has permission to download file
        if($file->type == 'private' && $file->user_id != $userId) {
            throw new AuthenticationException('You do not have permission to download this file');
        }

        $filePath = "{$userId}/{$file->storedName}";
        if (!Storage::exists($filePath)) {
            throw new FileNotFoundException;
        }
        // Return the file
        return Storage::path($filePath);

    }

    public static function getFile($fileId) {
        return File::findOrFail($fileId);
    }
}
