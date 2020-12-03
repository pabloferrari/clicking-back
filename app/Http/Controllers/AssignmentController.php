<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\AssignmentService;
use App\Http\Requests\AssignmentRequests\{CreateAssignmentRequest, UpdateAssignmentRequest};
use Log;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Assignments = AssignmentService::getAssignments();
        return response()->json(['data' => $Assignments]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAssignmentRequest $request)
    {
        try {
            $newAssignment = AssignmentService::createAssignment($request->all());
            Log::debug(__METHOD__ . ' - NEW Assignment CREATED ' . json_encode($newAssignment));
            return response()->json(['data' => $newAssignment]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating assignment"], 400);
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
        $Assignment = AssignmentService::getAssignment($id);
        return response()->json(['data' => $Assignment]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignmentRequest $request, $id)
    {
        try {
            $Assignment = AssignmentService::updateAssignment($id, $request->all());
            Log::debug(__METHOD__ . '- ASSIGNMENT UPDATE ' . json_encode($Assignment));
            return response()->json(['data' => $Assignment]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Assignment"], 400);
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
            $deleted = AssignmentService::deleteAssignment($id);
            Log::debug(__METHOD__ . ' - ASSIGNMENT DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Assignment"], 400);
        }
    }

    public function assignmentByCourse($id)
    {
        $Assignment = AssignmentService::getAssignmentByCourse($id);
        return response()->json(['data' => $Assignment]);
    }
}