<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SubjectRequests\{CreateSubjectRequest, UpdateSubjectRequest};
use App\Classes\SubjectService;
use App\Models\Course;
use Log;


class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subject =
            SubjectService::getSubjects();
        return response()->json(['data' => $subject]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubjectRequest $request)
    {
        try {
            $newSubject = SubjectService::createSubject($request->all());
            Log::debug(__METHOD__ . ' - NEW SUBJECT CREATED ' . json_encode($newSubject));
            return response()->json(['data' => $newSubject]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating subject"], 400);
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
        $subject = SubjectService::getSubject($id);
        return response()->json(['data' => $subject]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, $id)
    {
        try {
            $subject = SubjectService::updateSubject($id, $request->all());
            Log::debug(__METHOD__ . ' - SUBJECT UPDATED ' . json_encode($subject));
            return response()->json(['data' => $subject]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating subject"], 400);
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
            $itIsInUse = Course::where('subject_id', $id)->first();
            if($itIsInUse) return response()->json(['message' => ['Materia' => 'No se puede eliminar una materia que esta en uso por un curso.']], 422);

            $deleted = SubjectService::deleteSubject($id);
            Log::debug(__METHOD__ . ' - SUBJECT DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting subject"], 400);
        }
    }
}
