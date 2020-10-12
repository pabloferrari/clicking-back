<?php

namespace App\Http\Controllers;

use App\Classes\CommissionService;
use Illuminate\Http\Request;
use App\Http\Requests\CommissionRequests\{CreateCommissionRequest,UpdateCommissionRequest};
use Log;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = CommissionService::getCommissions();
        return response()->json(['data' => $commissions]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCommissionRequest $request)
    {
        try {
            $newCommission = CommissionService::createCommision($request->all());
            Log::debug(__METHOD__ . ' - NEW COMMISSION CREATED ' . json_encode($newCommission));
            return response()->json(['data' => $newCommission]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Commission"], 400);
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
        $commission = CommissionService::getCommission($id);
        return response()->json(['data' => $commission]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommissionRequest $request, $id)
    {
        try {
            $Commission = CommissionService::updateCommission($id, $request->all());
            Log::debug(__METHOD__ . ' - COMMISSION UPDATED ' . json_encode($Commission));
            return response()->json(['data' => $Commission]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Commission"], 400);
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
            $deleted = CommissionService::deleteCommission($id);
            Log::debug(__METHOD__ . ' - COMMISSION DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Commission"], 400);
        }
    }
}
