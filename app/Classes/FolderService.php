<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\Folder;

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

    public static function createFolder($data)
    {
        $newFolder = new Folder();
        $newFolder->name = $data['name'];
        $newFolder->course_id = $data['course_id'];
        $newFolder->path = str_replace(' ', '_', $data['name']);
        $newFolder->institution_id = Auth::user()->institution_id;
        $newFolder->save();
        return self::getFolder($newFolder->id);
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
