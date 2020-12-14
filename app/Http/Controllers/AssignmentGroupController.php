<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AssignmentGroupRequests\{CreateAssignmentGroupRequest, UpdateAssignmentGroupRequest};
use App\Classes\AssignmentGroupService;
use Log;

class AssignmentGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AssignmentGroups = AssignmentGroupService::getAssignmentGroups();
        return response()->json(['data' => $AssignmentGroups]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAssignmentGroupRequest $request)
    {
        try {
            $newAssignmentGroup = AssignmentGroupService::createAssignmentGroup($request->all());
            Log::debug(__METHOD__ . ' - NEW ASSIGNMENT GROUP CREATED ' . json_encode($newAssignmentGroup));
            return response()->json(['data' => $newAssignmentGroup]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Assignment group"], 400);
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
        $AssignmentGroup = AssignmentGroupService::getAssignmentGroup($id);
        return response()->json(['data' => $AssignmentGroup]);
    }

    public function assignmentGroupByAssignment($id)
    {
        try {
            $AssignmentGroup = AssignmentGroupService::getAssignmentGroupByAssignment($id);
            return response()->json(['data' => $AssignmentGroup]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "error"], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignmentGroupRequest $request, $id)
    {
        try {
            $AssignmentGroup = AssignmentGroupService::updateAssignmentGroup($id, $request->all());
            Log::debug(__METHOD__ . '- ASSIGNMENT GROUP UPDATE ' . json_encode($AssignmentGroup));
            return response()->json(['data' => $AssignmentGroup]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Assignment group"], 400);
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
            $deleted = AssignmentGroupService::deleteAssignmentGroup($id);
            Log::debug(__METHOD__ . ' - ASSIGNMENT GROUP DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Assignment group"], 400);
        }
    }
}
