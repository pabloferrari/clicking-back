<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CourseRequests\{CreateCourseRequest, UpdateCourseRequest};
use App\Classes\CourseService;
use Illuminate\Support\Facades\Auth;

use Log;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Course = CourseService::getCourses();
        return response()->json(['data' => $Course]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCourseRequest $request)
    {
        try {
            $newCourse = CourseService::createCourse($request->all());
            Log::debug(__METHOD__ . ' - NEW COURSE CREATED ' . json_encode($newCourse));
            return response()->json(['data' => $newCourse]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating course"], 400);
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
        $Course = CourseService::getCourse($id);
        return response()->json(['data' => $Course]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, $id)
    {
        try {
            $Course = CourseService::updateCourse($id, $request->all());
            Log::debug(__METHOD__ . ' - COURSE UPDATED ' . json_encode($Course));
            return response()->json(['data' => $Course]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating course"], 400);
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
            $deleted = CourseService::deleteCourse($id);
            Log::debug(__METHOD__ . ' - COURSE DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting course"], 400);
        }
    }

    public function coursesByClassroom($id)
    {
        try {
            $courses = CourseService::coursesByClassroom($id);
            return response()->json(['data' => $courses]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "error"], 400);
        }
    }

    public function courseClassesCount($id)
    {

        $courses = CourseService::getCourseClassesCount($id);
        return response()->json(['data' => $courses]);
    }
    public function myCourses(Request $request)
    {
        $user = Auth::user();
        try {

            if ($user->hasRole('teacher')) {
                $courses = CourseService::getCoursesTeacher();
            } elseif ($user->hasRole('student')) {
                $courses = CourseService::getCoursesStudent();
            } else {
                $courses = [];
            }

            return response()->json(['data' => $courses]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $user->id);
            return response()->json(["message" => "error"], 400);
        }
    }

    public function myCoursesAssignmentsCount()
    {
        $user = Auth::user();
        try {

            if ($user->hasRole('teacher')) {
                $courses = CourseService::getMyCoursesAssignmentsCountTeacher();
            } elseif ($user->hasRole('student')) {
                $courses = CourseService::getMyCoursesAssignmentsCountStudent();
            } else {
                $courses = [];
            }

            return response()->json(['data' => $courses]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $user->id);
            return response()->json(["message" => "error"], 400);
        }
    }

    public function storeStudentInCourse(Request $request)
    {
        try {
            $newStudentInCourse = CourseService::addStudentInCourse($request->all());
            Log::debug(__METHOD__ . ' - NEW STUDENT IN COURSES CREATED ' . json_encode($newStudentInCourse));
            return response()->json(['data' => $newStudentInCourse]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating student in courses"], 400);
        }
    }

    public function studentNotInCourse($id)
    {
        try {
            $courses = CourseService::getStudentNotInCourse($id);
            return response()->json(['data' => $courses]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "error"], 400);
        }
    }

    public function  deleteStudentCourse($id)
    {
        try {
            $deleted = CourseService::deleteStudentCourse($id);
            Log::debug(__METHOD__ . ' - STUDENT COURSE DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting student in course"], 400);
        }
    }
}
