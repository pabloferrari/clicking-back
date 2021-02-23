<?php

namespace App\Http\Controllers;

use App\Classes\FolderService;
use Illuminate\Http\Request;
use Log;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folders = FolderService::getFolders();
        return response()->json(['data' => $folders]);
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
            $newFolder = FolderService::createFolder($request->all(), $request);
            Log::debug(__METHOD__ . ' - NEW FOLDER CREATED ' . json_encode($newFolder));
            return response()->json(['data' => $newFolder]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating folder"], 400);
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
        $folder = FolderService::getFolder($id);
        return response()->json(['data' => $folder]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $folder = FolderService::updateFolder($id, $request->all());
            Log::debug(__METHOD__ . ' - FOLDER UPDATED ' . json_encode($folder));
            return response()->json(['data' => $folder]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating folder"], 400);
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
            $deleted = FolderService::deleteFolder($id);
            Log::debug(__METHOD__ . ' - FOLDER DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting folder"], 400);
        }
    }

    public function folderByCourse($id)
    {

        $folders = FolderService::getFolderByCourse($id);
        return response()->json(['data' => $folders]);
    }
}