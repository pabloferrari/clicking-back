<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\InstitutionYearService;
use App\Http\Requests\InstitutionsYearRequest\{CreateInstitutionsYearRequest,UpdateInstitutionsYearRequest};
use Log;

class InstitutionYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $institutionsYears = InstitutionYearService::getInstitutionsYears();
        return response()->json(['data' => $institutionsYears]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInstitutionsYearRequest $request)
    {

        try {
            $newInstitutionYear = InstitutionYearService::createInstitutionYear($request->all());
            Log::debug(__METHOD__ . ' - NEW INSTITUTION YEAR CREATED ' . json_encode($newInstitutionYear));
            return response()->json(['data' => $newInstitutionYear]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Institution Year"], 400);
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
        $InstitutionYear = InstitutionYearService::getInstitutionYear($id);
        return response()->json(['data' => $InstitutionYear]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstitutionsYearRequest $request, $id)
    {
        try {
            $InstitutionYear = InstitutionYearService::updateInstitutionYear($id, $request->all());
            Log::debug(__METHOD__ . ' - INSTITUTION YEAR UPDATED ' . json_encode($InstitutionYear));
            return response()->json(['data' => $InstitutionYear]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Institution Year"], 400);
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
            $deleted = InstitutionYearService::deleteInstitutionYear($id);
            Log::debug(__METHOD__ . ' - INSTITUTION YEAR DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Institution Year"], 400);
        }
    }
}
