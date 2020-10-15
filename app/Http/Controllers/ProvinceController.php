<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvinceRequests\{CreateProvinceRequest, UpdateProvinceRequest};
use App\Classes\ProvinceService;
use Log;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces =
            ProvinceService::getProvinces();
        return response()->json(['data' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProvinceRequest $request)
    {
        try {
            $newProvince = ProvinceService::createProvince($request->all());
            Log::debug(__METHOD__ . ' - NEW PROVINCE CREATED ' . json_encode($newProvince));
            return response()->json(['data' => $newProvince]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating province"], 400);
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
        $province = ProvinceService::getProvince($id);
        return response()->json(['data' => $province]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProvinceRequest $request, $id)
    {
        try {
            $province = ProvinceService::updateProvince($id, $request->all());
            Log::debug(__METHOD__ . ' - PROVINCE UPDATED ' . json_encode($province));
            return response()->json(['data' => $province]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating province"], 400);
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
            $deleted = ProvinceService::deleteProvince($id);
            Log::debug(__METHOD__ . ' - PROVINCE DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting province"], 400);
        }
    }
}
