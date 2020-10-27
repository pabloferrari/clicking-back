<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\AssignmentTypeService;
use App\Http\Requests\AssignmentTypeRequests\{CreateAssignmentTypeRequest, UpdateAssignmentTypeRequest};
use Log;

class AssignmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AssignmentTypes = AssignmentTypeService::getAssignmentTypes();
        return response()->json(['data' => $AssignmentTypes]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAssignmentTypeRequest $request)
    {
        try {
            $newAssignmentType = AssignmentTypeService::createAssignmentType($request->all());
            Log::debug(__METHOD__ . ' - NEW Assignment type CREATED ' . json_encode($newAssignmentType));
            return response()->json(['data' => $newAssignmentType]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Assignment type"], 400);
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
        $AssignmentType = AssignmentTypeService::getAssignmentType($id);
        return response()->json(['data' => $AssignmentType]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignmentTypeRequest $request, $id)
    {
        try {
            $AssignmentType = AssignmentTypeService::updateAssignmentType($id, $request->all());
            Log::debug(__METHOD__ . '- ASSIGNMENT TYPE UPDATE ' . json_encode($AssignmentType));
            return response()->json(['data' => $AssignmentType]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Assignment Type"], 400);
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
            $deleted = AssignmentTypeService::deleteAssignmentType($id);
            Log::debug(__METHOD__ . ' - ASSIGNMENT TYPE DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Assignment Type"], 400);
        }
    }
}
