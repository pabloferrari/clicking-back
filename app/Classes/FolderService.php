<?php

namespace App\Classes;

use App\Models\Folder;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;

class FolderService
{

    public static function getFolders()
    {
        return Folder::get();
    }

    public static function getFolder($id)
    {
        return Folder::where('id', $id)->first();
    }

    public static function createFolder($data, $request)
    {
        DB::beginTransaction();
        $newFolder = new Folder();
        $newFolder->name = $data['name'];
        $newFolder->course_id = $data['course_id'];
        $newFolder->path = str_replace(' ', '_', $data['name']);
        $newFolder->institution_id = Auth::user()->institution_id;
        $newFolder->save();

        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();
        $dataFile = array(
            'model_name' => 'Library',
            'model_id'   => $newFolder->id,
            'request'    => $request,
            'user_id'    => Auth::user()->id
        );
        $nameFolder = $newFolder->institution_id . '-' . $newFolder->course_id . '-' . $newFolder->name;
        $resultFile = $handleFilesUploadService->createFile($dataFile, $nameFolder);

        if ($resultFile) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE FOLDER ' . json_encode($resultFile));
            return self::getFolder($newFolder->id);
        } else {
            DB::rollback();
            return false;
        }
    }

    public static function updateFolder($id, $data)
    {
        $updateFolder = Folder::find($id);
        $updateFolder->name       = $data['name'];
        $updateFolder->path = str_replace(' ', '_', $data['name']);
        $updateFolder->course_id = $data['course_id'];
        $updateFolder->institution_id = Auth::user()->institution_id;
        $updateFolder->save();
        return self::getFolder($updateFolder->id);
    }

    public static function deleteFolder($id)
    {
        return Folder::where('id', $id)->delete();
    }

    public static function getFolderByCourse($id)
    {
        return Folder::where('course_id', $id)->get();
    }
}