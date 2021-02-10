<?php

namespace App\Classes;

use App\Models\Library;
use Log;
use Illuminate\Support\Facades\Auth;

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
        $newLibrary->article = $data['article'];
        $newLibrary->description = $data['description'];
        $newLibrary->user_id = Auth::user()->id;
        // $newLibrary->active = 1; // true
        $newLibrary->save();
        return self::getLibrary($newLibrary->id);
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
