<?php

namespace App\Classes;

use App\Models\Library;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;

class LibraryService
{

    public static function getLibraries()
    {
        // return Library::get();
        return DB::table('libraries')
            ->join('files', 'files.model_id', '=', 'libraries.id')
            ->where('files.model_name', 'Library')
            ->get();
    }

    public static function getLibrary($id)
    {
        return DB::table('libraries')
            ->join('files', 'files.model_id', '=', 'libraries.id')
            ->where('libraries.id', $id)
            ->where('files.model_name', 'Library')
            ->first();
    }

    public static function createLibrary($data, $request)
    {
        DB::beginTransaction();
        $newLibrary = new Library();
        $newLibrary->article = $data['article'];
        $newLibrary->description = $data['description'];
        $newLibrary->user_id = Auth::user()->id;
        // $newLibrary->active = 1; // true
        $newLibrary->save();

        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();
        $dataFile = array(
            'model_name' => 'Library',
            'model_id'   => $newLibrary->id,
            'request'    => $request,
            'user_id'    => Auth::user()->id
        );
        $resultFile = $handleFilesUploadService->createFile($dataFile);

        if ($resultFile) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE LIBRARY ' . json_encode($resultFile));
            return self::getLibrary($newLibrary->id);
        } else {
            DB::rollback();
            return false;
        }
    }

    public static function updateLibrary($id, $data)
    {
        $Library = Library::where('id', $id)->first();
        $Library->name   = $data['name'];
        $Library->description = $data['description'];
        // $Library->active = $data['active'];
        $Library->save();
        return $Library;
    }

    public static function deleteLibrary($id)
    {
        return Library::where('id', $id)->delete();
    }
}