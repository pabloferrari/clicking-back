<?php

namespace App\Http\Controllers;

use App\Classes\InstitutionService;
use App\Http\Requests\InstitutionRequests\{CreateInstitutionRequest, UpdateInstitutionRequest};
use Log;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutions = InstitutionService::getInstitutions();
        return response()->json(['data' => $institutions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInstitutionRequest $request)
    {
        try {
            $newInstitution = InstitutionService::createInstitution($request->all());
            Log::debug(__METHOD__ . ' - NEW INSTITUTION CREATED ' . json_encode($newInstitution));
            return response()->json(['data' => $newInstitution]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating institution"], 400);
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
        $institution = InstitutionService::getInstitution($id);
        return response()->json(['data' => $institution]);
    }

    public function institutionCount($id)
    {
        $institutionCount = InstitutionService::getInstitutionCount($id);
        return response()->json(['data' => $institutionCount]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstitutionRequest $request, $id)
    {
        try {
            $institution = InstitutionService::updateInstitution($id, $request->all());
            Log::debug(__METHOD__ . ' - INSTITUTION UPDATED ' . json_encode($institution));
            return response()->json(['data' => $institution]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating institution"], 400);
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
            $deleted = InstitutionService::deleteInstitution($id);
            Log::debug(__METHOD__ . ' - INSTITUTION DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting institution"], 400);
        }
    }
}
