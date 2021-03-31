<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\CourseClassService;
use App\Http\Requests\CourseClassRequests\{CreateCourseClassRequest, UpdateCourseClassRequest};
use Log;

class CourseClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CourseClasses = CourseClassService::getCourseClasses();
        return response()->json(['data' => $CourseClasses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCourseClassRequest $request)
    {
        try {
            $newCourseClass = CourseClassService::createCourseClass($request->all());
            Log::debug(__METHOD__ . ' - NEW CLASS CREATED ' . json_encode($newCourseClass));
            return response()->json(['data' => $newCourseClass]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Class"], 400);
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
        $CourseClass = CourseClassService::getCourseClass($id);
        return response()->json(['data' => $CourseClass]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseClassRequest $request, $id)
    {
        try {
            $courseClass = CourseClassService::updateCourseClass($id, $request->all());
            Log::debug(__METHOD__ . '- CLASS UPDATE ' . json_encode($courseClass));
            return response()->json(['data' => $courseClass]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating class"], 400);
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
            $deleted = CourseClassService::deleteCourseClass($id);
            Log::debug(__METHOD__ . ' - CLASS DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting class"], 400);
        }
    }

    public function classAssignmentCount($id)
    {
        $CourseClasses = CourseClassService::getCourseClassInstitutionCount($id);
        return response()->json(['data' => $CourseClasses]);
    }

    public function courseClassByStudents($id)
    {
        $CourseClasses = CourseClassService::getCourseClassByStudents($id);
        return response()->json(['data' => $CourseClasses]);
    }
}
