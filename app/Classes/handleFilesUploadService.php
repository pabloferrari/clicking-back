<?php

namespace App\Classes;

use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();
        $resultFile = $handleFilesUploadService->uploadFile($data['request']);

        if ($resultFile) {
            $new = new File();
            $new->name        =  trim(str_replace('public/', '', $resultFile)); //Generate
            $new->model_name  = $data['model_name']; // Arg
            $new->model_id    = $data['model_id']; // Arg
            $new->path        = Storage::path($resultFile); //Generate
            $new->remote_path = Storage::path($resultFile); //Generate
            $new->url         = Storage::url($resultFile); //Generate
            $new->migrated    = 0; //Generate
            $new->user_id     = Auth::user()->id; //Generate
            $new->status      = '1'; //Generate
            $new->size        = Storage::size($resultFile); //Generate
            #$new->extension   = $resultFile->extension();

            $new->save();
            return self::getFile($new->id);
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

    public static function uploadFile($request)
    {
        // storage file client
        if ($request) {
            $result = $request->file('file')->store('public');
            // Format result return $result = public\ICR5n2x03LTg5jttGbmpsPern4QdyPTH7OEKFgUD.jpeg
            Log::debug(__METHOD__ . ' -> Upload file storage Create ' . json_encode($result));
            return $result;
        } else {
            Log::debug(__METHOD__ . ' -> Upload file storage $request empty ' . json_encode($request));
            return false;
        }
    }

    public static function deleteFile($id)
    {
        return File::where('id', $id)->delete();
    }
}