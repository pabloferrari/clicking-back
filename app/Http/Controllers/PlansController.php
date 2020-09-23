<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\PlanService;
use App\Http\Requests\PlanRequests\CreatePlanRequest;
use App\Http\Requests\PlanRequests\UpdatePlanRequest;
use Log;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = PlanService::getPlans();
        return response()->json(['plans' => $plans]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePlanRequest $request)
    {
        try {
            $newPlan = PlanService::createPlan($request->all());
            Log::debug(__METHOD__ . ' - NEW PLAN CREATED ' . json_encode($newPlan));
            return response()->json($newPlan);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating plan"], 400);
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
        $plan = PlanService::getPlan($id);
        return response()->json($plan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlanRequest $request, $id)
    {
        try {
            $plan = PlanService::updatePlan($id, $request->all());
            Log::debug(__METHOD__ . ' - PLAN UPDATED ' . json_encode($plan));
            return response()->json($plan);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating plan"], 400);
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
            $deleted = PlanService::deletePlan($id);
            Log::debug(__METHOD__ . ' - PLAN DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting plan"], 400);
        }
    }
}
