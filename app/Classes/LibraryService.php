<?php

namespace App\Classes;

use App\Models\Library;
use App\Classes\Helpers;
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Classes\UserService;

class LibraryService
{

    public static function getLibraries()
    {
        $userService = new UserService();
        $users = $userService->getUsersByInstitutionId(Auth::user()->institution_id);
        
        return DB::table('libraries')
            ->join('files', 'files.model_id', '=', 'libraries.id')
            ->where('files.model_name', 'Library')
            ->whereIn('libraries.user_id', $users)
            ->get();
    }

    public static function getLibrary($id)
    {
        $userService = new UserService();
        $users = $userService->getUsersByInstitutionId(Auth::user()->institution_id);

        return DB::table('libraries')
            ->join('files', 'files.model_id', '=', 'libraries.id')
            ->where('libraries.id', $id)
            ->where('files.model_name', 'Library')
            ->whereIn('libraries.user_id', $users)
            ->first();
    }

    public static function createLibrary($data, $request)
    {
        DB::beginTransaction();
        try {
        
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
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE LIBRARY ' . json_encode($resultFile));

            DB::commit();
            return self::getLibrary($newLibrary->id);
            
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' ' . Helpers::lsi() . " " . $th->getMessage() . " " . json_encode($data));
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