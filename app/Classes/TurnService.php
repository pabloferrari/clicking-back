<?php

namespace App\Classes;

use App\Models\Turn;

class TurnService
{

    public static function getTurns()
    {
        return Turn::with(['institution'])->get();
    }

    public static function getTurn($id)
    {
        return  Turn::with(['institution'])->find($id);
    }

    public static function createTurn($data)
    {
        $newTurn = new Turn();
        $newTurn->name = $data['name'];
        $newTurn->institution_id = $data['institution_id'];
        $newTurn->save();
        return self::getTurn($newTurn->id);
    }

    public static function updateTurn($id, $data)
    {
        Turn::where('id', $id)->update($data);
        return self::getTurn($id);;
    }

    public static function deleteTurn($id)
    {
        return Turn::where('id', $id)->delete();
    }

}
