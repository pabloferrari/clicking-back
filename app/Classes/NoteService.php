<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Models\NoteContent;
use DB;
use Log;

class NoteService
{

    public static function getAll()
    {
        return Note::where('user_id', Auth::user()->id)
            ->with(['noteContents'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function getNote($id)
    {
        return Note::where('id', $id)
            ->with(['noteContents'])
            ->first();
    }

    public static function createNote($data)
    {
        DB::beginTransaction();
        try {
            $newNote = new Note();
            $newNote->title    = $data['title'];
            $newNote->color    = $data['color'];
            $newNote->user_id  = Auth::user()->id;
            $newNote->save();

            foreach ($data['contents'] as $value) {
                $newContent = new NoteContent();
                $newContent->note_id = $newNote->id;
                $newContent->content = $value['content'];
                $newContent->type = $value['type'];
                $newContent->save();
            }
            DB::commit();

            return self::getNote($newNote->id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK  create note ' . json_encode($data) . ' exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function updateNote($id, $data)
    {
        DB::beginTransaction();
        try {
            $note = self::getNote($id);
            $note->title    = $data['title'];
            $note->color    = $data['color'];
            $note->user_id  = Auth::user()->id;
            $note->save();
            NoteContent::where('note_id', $id)->delete();
            foreach ($data['contents'] as $value) {
                $newContent = new NoteContent();
                $newContent->note_id = $id;
                $newContent->content = $value['content'];
                $newContent->type = $value['type'];
                $newContent->save();
            }
            DB::commit();
            return self::getNote($id);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK update note ' . json_encode($data) . ' exception: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function deleteNote($id)
    {
        DB::beginTransaction();
        try {
            NoteContent::where('note_id', $id)->delete();
            $note = Note::where('id', $id)->delete();
            DB::commit();
            return $note;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(__METHOD__ . ' - ROLLBACK  delete note ' . json_encode($id) . ' exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
