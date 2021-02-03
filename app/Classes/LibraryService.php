<?php

namespace App\Classes;

use App\Models\Library;
use Log;

class LibraryService
{

    public static function getLibraries()
    {
        return Library::get();
    }

    public static function getLibrary($id)
    {
        return Library::where('id', $id)->first();
    }

    public static function createLibrary($data)
    {
        $newLibrary = new Library();
        $newLibrary->name = $data['name'];
        $newLibrary->active = 1; // true
        $newLibrary->save();
        return $newLibrary;
    }

    public static function updateLibrary($id, $data)
    {
        $Library = Library::where('id', $id)->first();
        $Library->name   = $data['name'];
        $Library->active = $data['active'];
        $Library->save();
        return $Library;
    }

    public static function deleteLibrary($id)
    {
        return Library::where('id', $id)->delete();
    }
}
