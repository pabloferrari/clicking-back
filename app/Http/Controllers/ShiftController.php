<?php

namespace App\Http\Controllers;

use App\Classes\ShiftService;
use Illuminate\Http\Request;
use App\Http\Requests\ShiftRequests\{CreateShiftRequest, UpdateShiftRequest};
use Log;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = ShiftService::getShifts();
        return response()->json(['data' => $shifts]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateShiftRequest $request)
    {
        try {
            $newShift = ShiftService::createShift($request->all());
            Log::debug(__METHOD__ . ' - NEW SHIFT CREATED ' . json_encode($newShift));
            return response()->json(['data' => $newShift]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Shift"], 400);
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
        $turn = ShiftService::getShift($id);
        return response()->json(['data' => $turn]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShiftRequest $request, $id)
    {
        try {
            $shift = ShiftService::updateShift($id, $request->all());
            Log::debug(__METHOD__ . ' - SHIFT UPDATED ' . json_encode($shift));
            return response()->json(['data' => $shift]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating shift"], 400);
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
            $deleted = ShiftService::deleteShift($id);
            Log::debug(__METHOD__ . ' - SHIFT DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Shift"], 400);
        }
    }
}
