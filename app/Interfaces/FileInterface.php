<?php

namespace App\Interfaces;

interface FileInterface
{
    public function upload($userId, $file, $type);
    public function getFileFromDirectory($userId, $file);
    public function getFile($fileId);
    public function updateFile($fileId, $fileName, $fileType);
    public function deleteFile($userId, $fileId);
    public function getUserFiles($userId);
}
