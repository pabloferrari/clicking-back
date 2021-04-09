<?php

namespace App\Classes;

use App\Models\{Folder, File};
use Log;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Classes\{UserService, CourseService};
use Illuminate\Support\Str;

class FolderService
{

    public static function getFolders()
    {
        // return Folder::get();
        #$userService = new UserService();
        #$users = $userService->getUsersByInstitutionId(Auth::user()->institution_id);
        $institution_id = Auth::user()->institution_id;

        return DB::table('folders')
            ->join('files', 'files.model_id', '=', 'folders.id')
            ->where('files.model_name', 'Folder')
            ->where('folders.institution_id', $institution_id)
            ->where('folders.deleted_at', null)
            ->where('files.deleted_at', null)
            ->select(
                'folders.*',
                'files.*',
                'folders.name AS folders_name'
            )
            ->get();
    }

    public static function getFolder($id)
    {
        // return Folder::where('id', $id)->first();
        $institution_id = Auth::user()->institution_id;

        return DB::table('folders')
            ->join('files', 'files.model_id', '=', 'folders.id')
            ->where('files.model_name', 'Folder')
            ->where('folders.id', $id)
            ->where('folders.institution_id', $institution_id)
            ->where('folders.deleted_at', null)
            ->where('files.deleted_at', null)
            ->select(
                'folders.*',
                'files.*',
                'folders.name AS folders_name'
            )
            ->get();
    }

    public static function getFolderFirst($id)
    {
        $couseService = new CourseService();
        $institution_id = Auth::user()->institution_id;
        $folder = DB::table('folders')->where('folders.id', $id)->where('folders.institution_id', $institution_id)
            ->select('folders.*', 'folders.name AS folders_name')->first();
        try {
            $course = $couseService->getSubjectByCourseId($folder->course_id);
            $folder->subject = $course->subject->name;
        } catch (\Throwable $th) {
            $folder->subject = false;
        }
        return $folder;
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
            'model_name' => 'Folder',
            'model_id'   => $newFolder->id,
            'request'    => $request,
            'user_id'    => Auth::user()->id
        );
        $nameFolder = Str::slug($newFolder->institution_id . ' ' . $newFolder->course_id . ' ' . $newFolder->name, '-');
        Log::debug("NAME FOLDER $nameFolder");
        $resultFile = $handleFilesUploadService->createFile($dataFile, $nameFolder);

        if ($resultFile) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE FOLDER ' . json_encode($resultFile));
            return self::getFolderFirst($newFolder->id);
        } else {
            DB::rollback();
            return false;
        }
    }

    public static function createFolderEmpty($data)
    {
        $newFolder = new Folder();
        $newFolder->name = $data['name'];
        $newFolder->course_id = $data['course_id'];
        $newFolder->path = str_replace(' ', '_', $data['name']);
        $newFolder->institution_id = Auth::user()->institution_id;
        $newFolder->save();
        return $newFolder;
    }

    public static function getFolderByClass($class)
    {
        return Folder::where('name', $class->title)->where('course_id', $class->course_id)->first();
    }

    public static function createFileFolder($data, $request)
    {
        DB::beginTransaction();
        // $newFolder = new Folder();
        // $newFolder->name = $data['name'];
        // $newFolder->course_id = $data['course_id'];
        // $newFolder->path = str_replace(' ', '_', $data['name']);
        // $newFolder->institution_id = Auth::user()->institution_id;
        // $newFolder->save();
        $data = self::getFolderFirst($data['folder_id']);

        // Load File FileUpload
        $handleFilesUploadService = new handleFilesUploadService();
        $dataFile = array(
            'model_name' => 'Folder',
            'model_id'   => $data->id,
            'request'    => $request,
            'user_id'    => Auth::user()->id
        );
        $nameFolder = Str::slug($data->institution_id . ' ' . $data->course_id . ' ' . $data->name, '-');
        Log::debug("NAME FOLDER $nameFolder");
        $resultFile = $handleFilesUploadService->createFile($dataFile, $nameFolder);

        if ($resultFile) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> NEW UPLOAD FILE FOLDER ADD+' . json_encode($resultFile));
            return self::getFolder($data->id);
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

    public static function deleteFileFolder($id)
    {
        // Load File FileUpload

        //$data = self::getFolderFirst($id);
        $data = File::where('id', '=', $id)->get();

        if ($data) {
            DB::beginTransaction();
            $handleFilesUploadService = new handleFilesUploadService();

            $result = $handleFilesUploadService->deleteFile($id);
            $resultFile = $handleFilesUploadService->deleteFileStorage($data[0]->name);

            if ($result && $resultFile) {
                DB::commit();
                Log::debug(__METHOD__ . ' -> DELETE FILE FOLDER' . json_encode($id));
                return self::getFolder($data[0]->model_id);
            } else {
                DB::rollback();
                return false;
            }
        } else {
            return false;
        }
    }

    public static function deleteCourseFolder($id)
    {
        // Load File FileUpload

        $data = self::getFolderFirst($id);

        DB::beginTransaction();
        $handleFilesUploadService = new handleFilesUploadService();

        $result = Folder::where('id', $id)->delete();

        if ($result) {
            DB::commit();
            Log::debug(__METHOD__ . ' -> DELETE FOLDER' . json_encode($id));
            return self::getFolderByCourse($data->course_id);
        } else {
            DB::rollback();
            return false;
        }
    }


    public static function getFolderByCourse($id)
    {
        //return Folder::where('course_id', $id)->get();
        $institution_id = Auth::user()->institution_id;

        return DB::table('folders')
            ->where('folders.course_id', $id)
            ->where('folders.institution_id', $institution_id)
            ->where('folders.deleted_at', null)
            ->select(
                'folders.*',
                'folders.name AS folders_name'
            )
            ->get();
    }
}
