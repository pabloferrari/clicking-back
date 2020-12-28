<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\StudentService;
use App\Http\Requests\StudentRequests\{CreateStudentRequest, UpdateStudentRequest};
use Log;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = StudentService::getStudents();
        return response()->json(['data' => $students]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentRequest $request)
    {
        try {
            $newStudent = StudentService::createStudent($request->all());
            Log::debug(__METHOD__ . ' - NEW STUDENT CREATED ' . json_encode($newStudent));
            return response()->json(['data' => $newStudent]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating student"], 400);
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
        $student = StudentService::getStudent($id);
        return response()->json(['data' => $student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        try {
            $Student = StudentService::updateStudent($id, $request->all());
            Log::debug(__METHOD__ . ' - STUDENT UPDATED ' . json_encode($Student));
            return response()->json(['data' => $Student]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Student"], 400);
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
            $deleted = StudentService::deleteStudent($id);
            Log::debug(__METHOD__ . ' - STUDENT DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting student"], 400);
        }
    }

    /**
     * Display Listing Students By Institution ID
     * @param int $id
     * @return \Iluminate\Http\Response
     */

    public function studentsByInstitution($id)
    {
        $students = StudentService::getStudentsByInstitution($id);
        return response()->json(['data' => $students]);
    }
}
