<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClassroomRequests\{CreateClassroomRequest, UpdateClassroomRequest};
use App\Classes\ClassroomService;
use Log;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Classroom = ClassroomService::getClassrooms();
        return response()->json(['data' => $Classroom]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateClassroomRequest $request)
    {
        try {
            $newClassroom = ClassroomService::createClassroom($request->all());
            Log::debug(__METHOD__ . ' - NEW CLASSROOM CREATED ' . json_encode($newClassroom));
            return response()->json(['data' => $newClassroom]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating classroom"], 400);
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
        // $Classroom = ClassroomService::getClassroom($id);
        // return response()->json(['data' => $Classroom]);

        $ClassroomInstitution = ClassroomService::getClassroomInstitution($id);
        return response()->json(['data' => $ClassroomInstitution]);
    }


    public function classroomCount($id)
    {

        $ClassroomCount = ClassroomService::getClassroomCount($id);
        return response()->json(['data' => $ClassroomCount]);
    }

    /**
     * Display the specified ClassRoom Institution resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function classroomInstitution($id)
    {
        $ClassroomInstitution = ClassroomService::getClassroomInstitution($id);
        return response()->json(['data' => $ClassroomInstitution]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClassroomRequest $request, $id)
    {
        try {
            $Classroom = ClassroomService::updateClassroom($id, $request->all());
            Log::debug(__METHOD__ . ' - CLASSROOM UPDATED ' . json_encode($Classroom));
            return response()->json(['data' => $Classroom]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating classroom"], 400);
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
            $deleted = ClassroomService::deleteClassroom($id);
            Log::debug(__METHOD__ . ' - CLASSROOM DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting classroom"], 400);
        }
    }
}
