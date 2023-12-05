<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Models\User;
use App\Interfaces\FileInterface;
use App\Exceptions\StorageLimitExceededException;

class FileService implements FileInterface
{
    public static function upload($userId, $file, $type)
    {
        // Check if user has enough storage
        $user = User::find($userId);
        $usedUsedStorage = $user->files->pluck('size')->sum();
        $maxUserStorage = $user->allowedStorageGB *1000000000;//convert gigabytes to bytes
        if($file->getSize()+$usedUsedStorage > $maxUserStorage) {
            throw new StorageLimitExceededException('You do not have enough storage to upload this file');
        }

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

    public static function getFileFromDirectory($userId, $file)
    {
        // Check if user has permission to download file
        if ($file->type == 'private' && $file->user_id != $userId) {
            throw new AuthenticationException('You do not have permission to download this file');
        }

        $filePath = "{$file->user_id}/{$file->storedName}";
        if (!Storage::exists($filePath)) {
            throw new FileNotFoundException;
        }
        // Return the file
        return Storage::path($filePath);
    }

    public static function getFile($fileId)
    {
        return File::findOrFail($fileId);
    }

    public static function updateFile($fileId, $fileName, $fileType)
    {
        $file = File::findOrFail($fileId);
        $file->name = $fileName;
        $file->type = $fileType;
        $file->save();
    }
    public static function deleteFile($userId, $fileId)
    {


        $file = File::findOrFail($fileId);
        if ($file->user_id != $userId) {
            throw new AuthenticationException('You do not have permission to delete this file');
        }
        // First delete the file from the directory
        Storage::delete(self::getFileFromDirectory($userId, $file));
        // Delete the file from the files tables
        $file->delete();

    }

    public static function getUserFiles($userId)
    {
        //TODO: get all file names of userId folder and get all files from files tables using the file names
        //it is possible that the userFiles folder will be deleted

        // Load all the users files
        return User::find($userId)->files;
    }
}
