<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Models\NoteContent;
use DB;
use Log;

class NoteContentService
{
    public static function getNoteContent($id)
    {
        return NoteContent::where('id', $id)->first();
    }

    public static function createNoteContent($data)
    {
        $newContent = new NoteContent();
        $newContent->note_id = $data['note_id'];
        $newContent->content = $data['content'];
        $newContent->type = $data['type'];
        $newContent->save();

        return $newContent;
    }

    public static function updateNoteContent($id, $data)
    {
        $noteContent = NoteContent::where('id', $id)->first();
        $noteContent->note_id = $data['note_id'];
        $noteContent->content = $data['content'];
        $noteContent->type = $data['type'];
        $noteContent->save();
        return $noteContent;
    }

    public static function deleteNoteContent($id)
    {
        return NoteContent::where('id', $id)->delete();
    }
}
