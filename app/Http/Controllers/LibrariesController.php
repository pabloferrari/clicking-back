<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\LibraryService;
use Log;

class LibrariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Libraries = LibraryService::getLibraries();
        return response()->json(['data' => $Libraries]);
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

            $Library = LibraryService::createLibrary($request->all());
            Log::debug(__METHOD__ . ' - NEW Library CREATED ' . json_encode($Library));
            return response()->json(['data' => $Library]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Library"], 400);
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
        $Library = LibraryService::getLibrary($id);
        return response()->json(['data' => $Library]);
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
            $Library = LibraryService::updateLibrary($id, $request->all());
            Log::debug(__METHOD__ . '- Library UPDATE ' . json_encode($Library));
            return response()->json(['data' => $Library]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Library"], 400);
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
            $deleted = LibraryService::deleteLibrary($id);
            Log::debug(__METHOD__ . ' - Library DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Library"], 400);
        }
    }
}
