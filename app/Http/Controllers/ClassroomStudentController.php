<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

#use App\Http\Requests\SubjectRequests\{CreateSubjectRequest, UpdateSubjectRequest};
use App\Classes\ClassroomStudentService;
use Log;

class ClassroomStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ClassroomStudent =
            ClassroomStudentService::getClassroomStudents();
        return response()->json(['data' => $ClassroomStudent]);
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
            $newClassroomStudent = ClassroomStudentService::createClassroomStudent($request->all());
            Log::debug(__METHOD__ . ' - NEW CLASSROOM STUDENT CREATED ' . json_encode($newClassroomStudent));
            return response()->json(['data' => $newClassroomStudent]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating classroom student"], 400);
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
        $ClassroomStudent = ClassroomStudentService::getClassroomStudent($id);
        return response()->json(['data' => $ClassroomStudent]);
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
            $ClassroomStudent = ClassroomStudentService::updateClassroomStudent($id, $request->all());
            Log::debug(__METHOD__ . ' - CLASSROOM STUDENT UPDATED ' . json_encode($ClassroomStudent));
            return response()->json(['data' => $ClassroomStudent]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating classroom student"], 400);
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
            $deleted = ClassroomStudentService::deleteClassroomStudent($id);
            Log::debug(__METHOD__ . ' - CLASSROOM STUDENT DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting classroom student"], 400);
        }
    }

    /**
     * Display the ratings students in specified course types ID
     * @param int $course_type_id
     * @return \Illuminate\Http\Response
     */

    public function ratingStudent($course_type_id)
    {
        $RatingStudent = ClassroomStudentService::getRatingStudent($course_type_id);
        return response()->json(['data' => $RatingStudent]);
    }
}
