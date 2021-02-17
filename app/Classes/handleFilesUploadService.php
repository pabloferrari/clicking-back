<?php

namespace App\Classes;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Classes\Helpers;
use Log;

class handleFilesUploadService
{

    public static function getFiles()
    {
        return File::with(['user'])->get();
    }

    public static function getFile($id)
    {
        return File::where('id', $id)->with(['user'])->first();
    }

    public static function createFile($data)
    {

        if (!$data['request']->file('files')) {
            return true; // temporalmente
        }
        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();

        $resultFile = [];

        foreach ($data['request']->file('files') as $file) {
            $resultFile[] = $handleFilesUploadService->uploadFile($file);
        }

        if ($resultFile) {
            $resp = true;
            foreach ($resultFile as $value) {
                if ($value) { // Nota TDD: Se debe trabajar como una transacción... (FALTA)

                    $urlFile = env('APP_URL') . Storage::url($value);
                    Log::debug(__METHOD__ . ' ' . Helpers::lsi() . ' File ' . $urlFile);
                    $new = new File();
                    $new->name        =  trim(str_replace('public/', '', $value)); //Generate
                    $new->model_name  = $data['model_name']; // Arg
                    $new->model_id    = $data['model_id']; // Arg
                    $new->path        = $urlFile; //Generate
                    $new->remote_path = $urlFile; //Generate
                    $new->url         = $urlFile; //Generate
                    $new->migrated    = 0; //Generate
                    $new->user_id     = $data['user_id']; // Arg
                    $new->status      = '1'; //Generate
                    $new->size        = Storage::size($value); //Generate
                    #$new->extension   = $resultFile->extension();

                    $new->save();

                    if (!$new->id) {
                        $resp = false;
                    }
                } else {
                    return false;
                }
            }
            if ($resp) {
                return self::getFile($new->id);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function updateFile($id, $data)
    {
        $file = self::getFile($id);
        $file->name        = $data['name'];
        $file->model_name  = $data['model_name']; // Arg
        $file->model_id    = $data['model_id']; // Arg
        $file->path        = $data['path'];
        $file->remote_path = $data['remote_path'];
        $file->url         = $data['url'];
        $file->migrated    = $data['migrated'];
        $file->user_id     = $data['user_id'];
        $file->status      = $data['status'];
        $file->size        = $data['size'];

        $file->save();
        return $file;
    }

    public static function uploadFile($file)
    {
        // storage file client
        if ($file) {
            $result = Storage::disk('local')->put('public', $file);

            // Format result return $result = public\ICR5n2x03LTg5jttGbmpsPern4QdyPTH7OEKFgUD.jpeg
            Log::debug(__METHOD__ . ' -> Upload file storage Create ' . json_encode($result));
            return $result;
        } else {
            Log::debug(__METHOD__ . ' -> Upload file storage $request empty ' . json_encode($file));
            return false;
        }
    }

    public static function deleteFileStorage($path = null)
    {
        $result = Storage::disk('public')->delete($path);
        Log::debug(__METHOD__ . ' -> Delete file storage disk ' . json_encode($result));
        return $result;
    }

    public static function deleteFile($id)
    {
        return File::where('id', $id)->delete();
    }
}
