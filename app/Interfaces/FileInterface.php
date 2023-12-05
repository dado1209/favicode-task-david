<?php

namespace App\Interfaces;

interface FileInterface
{
    public static function upload($userId, $file, $type);
    public static function getFileFromDirectory($userId, $file);
    public static function getFile($fileId);
    public static function updateFile($fileId, $fileName, $fileType);
    public static function deleteFile($userId, $fileId);
    public static function getUserFiles($userId);
}
