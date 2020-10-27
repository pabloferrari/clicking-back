<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CourseTypeRequests\{CreateCourseTypeRequest, UpdateCourseTypeRequest};
use App\Classes\CourseTypeService;
use Log;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CourseType =
            CourseTypeService::getCourseTypes();
        return response()->json(['data' => $CourseType]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCourseTypeRequest $request)
    {
        try {
            $newCourseType = CourseTypeService::createCourseType($request->all());
            Log::debug(__METHOD__ . ' - NEW COURSE TYPE CREATED ' . json_encode($newCourseType));
            return response()->json(['data' => $newCourseType]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating course type"], 400);
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
        $CourseType = CourseTypeService::getCourseType($id);
        return response()->json(['data' => $CourseType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseTypeRequest $request, $id)
    {
        try {
            $CourseType = CourseTypeService::updateCourseType($id, $request->all());
            Log::debug(__METHOD__ . ' - COURSE TYPE UPDATED ' . json_encode($CourseType));
            return response()->json(['data' => $CourseType]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating course type"], 400);
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
            $deleted = CourseTypeService::deleteCourseType($id);
            Log::debug(__METHOD__ . ' - COURSE TYPE DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting course type"], 400);
        }
    }
}
