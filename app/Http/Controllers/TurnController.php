<?php

namespace App\Http\Controllers;

use App\Classes\TurnService;
use Illuminate\Http\Request;
use App\Http\Requests\TurnRequests\{CreateTurnRequest,UpdateTurnRequest};
use Log;
class TurnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $turns = TurnService::getTurns();
        return response()->json(['data' => $turns]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTurnRequest $request)
    {
        try {
            $newTurn = TurnService::createTurn($request->all());
            Log::debug(__METHOD__ . ' - NEW TURN CREATED ' . json_encode($newTurn));
            return response()->json(['data' => $newTurn]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating Turn"], 400);
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
        $turn = TurnService::getTurn($id);
        return response()->json(['data' => $turn]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTurnRequest $request, $id)
    {
        try {
            $Turn = TurnService::updateTurn($id, $request->all());
            Log::debug(__METHOD__ . ' - TURN UPDATED ' . json_encode($Turn));
            return response()->json(['data' => $Turn]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating Turn"], 400);
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
            $deleted = TurnService::deleteTurn($id);
            Log::debug(__METHOD__ . ' - TURN DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting Turn"], 400);
        }
    }
}
