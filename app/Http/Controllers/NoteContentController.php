<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\NoteContentService;
use Log;

class NoteContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $notes = NoteService::getAll();
        // return response()->json(['data' => $notes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $note = NoteContentService::createNoteContent($request->all(), $request);
            Log::debug(__METHOD__ . ' - NEW note content CREATED ' . json_encode($note));
            return response()->json(['data' => $note]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating News", "error" => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = NoteContentService::getNoteContent($id);
        return response()->json(['data' => $note]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $note = NoteContentService::updateNoteContent($id, $request->all());
            Log::debug(__METHOD__ . '- note content UPDATE ' . json_encode($note));
            return response()->json(['data' => $note]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Note"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = NoteContentService::deleteNoteContent($id);
            Log::debug(__METHOD__ . ' - note content DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Note"], 400);
        }
    }
}
