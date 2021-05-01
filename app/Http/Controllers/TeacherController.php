<?php

namespace App\Http\Controllers;

use App\Classes\TeacherService;
use App\Http\Requests\TeacherRequests\{CreateTeacherRequest, UpdateTeacherRequest};
use App\Models\Course;
use Log;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $teachers = TeacherService::getTeachers();
        return response()->json(['data' => $teachers]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeacherRequest $request)
    {

        try {
            $newTeacher = TeacherService::createTeacher($request->all());
            Log::debug(__METHOD__ . ' - NEW TEACHER CREATED ' . json_encode($newTeacher));
            return response()->json(['data' => $newTeacher]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating teacher"], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = TeacherService::getTeacher($id);
        return response()->json(['data' => $teacher]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, $id)
    {
        try {
            $Teacher = TeacherService::updateTeacher($id, $request->all());
            Log::debug(__METHOD__ . ' - TEACHER UPDATED ' . json_encode($Teacher));
            return response()->json(['data' => $Teacher]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Teacher"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $itIsInUse = Course::where('teacher_id', $id)->first();
            if($itIsInUse) return response()->json(['message' => 'No se puede eliminar un profesor que esta en uso en un curso.'], 422);

            $deleted = TeacherService::deleteTeacher($id);
            Log::debug(__METHOD__ . ' - TEACHER DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting teacher"], 400);
        }
    }

    /**
     * Display a listing of the teacher by Institution ID.
     * @param  number  $Id
     * @return \Illuminate\Http\Response
     */
    public function teacherByInstitution($id)
    {
        $teachers = TeacherService::getTeachersByInstitution($id);
        return response()->json(['data' => $teachers]);
    }
}
