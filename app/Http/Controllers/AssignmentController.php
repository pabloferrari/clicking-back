<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\AssignmentService;
use App\Http\Requests\AssignmentRequests\{CreateAssignmentRequest, UpdateAssignmentRequest};
use Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        # CreateAssignmentRequest
        try {
            $newAssignment = AssignmentService::createAssignment($request->all(), $request);
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

    public function myAssignments($id, $status)
    {
        $user = Auth::user();
        if ($user->hasRole('teacher')) {
            $Assignment = AssignmentService::getAssignmentByTeacher($id, $status);
        } else if ($user->hasRole('student')) {
            $Assignment = AssignmentService::getAssignmentByStudent($id, $status);
        } else {
            $Assignment = [];
        }
        return response()->json(['data' => $Assignment]);
    }

    public function assignmentDetail($id)
    {
        $Assignment = AssignmentService::getAssignmentDetailById($id);
        return response()->json(['data' => $Assignment]);
    }

    public function storeAssignmentStudent(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user->hasRole('teacher')) {
                $newAssignment = AssignmentService::createAssignmentStudent($request->all());
            } else if ($user->hasRole('student')) {
                $newAssignment = AssignmentService::deliverAssignmentStudent($request->all(), $request);
            } else {
                $newAssignment = [];
            }

            Log::debug(__METHOD__ . ' - NEW Assignment student CREATED ' . json_encode($newAssignment));
            return response()->json(['data' => $newAssignment]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating assignment"], 400);
        }
    }

    public function getAssignmentTeacher($id, $userId)
    {
        $request = array(
            'id'         => $id,
            'user_Id'     => $userId,
            'model_name' => 'Assignment',
            'status'     => 1
        );

        $Assignment = AssignmentService::getAssignmentTeacherId($request);
        return response()->json(['data' => $Assignment]);
    }

    public function getAssignmentStudent($id, $userId)
    {
        $request = array(
            'id'         => $id,
            'user_id'     => $userId,
            'model_name' => 'Assignment',
            'status'     => 1
        );

        $Assignment = AssignmentService::getAssignmentStudentId($request);
        return response()->json(['data' => $Assignment]);
    }

    public function deleteFileStudent($id, $assignment_id, $user_id)
    {
        try {
            $deleted = AssignmentService::deletefileStudent($id, $assignment_id, $user_id);
            Log::debug(__METHOD__ . ' - ASSIGNMENT DELETED FILE id: ' . $id);
            return response()->json(['data' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Assignment file"], 400);
        }
    }
}
