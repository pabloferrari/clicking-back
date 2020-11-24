<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use App\Models\Shift;

class ShiftService
{

    public static function getShifts()
    {
        return Shift::with(['institution'])->where('institution_id', Auth::user()->institution_id)->get();
    }

    public static function getShift($id)
    {
        return  Shift::with(['institution'])->where('institution_id', Auth::user()->institution_id)->find($id);
    }

    public static function createShift($data)
    {
        $newShift = new Shift();
        $newShift->name = $data['name'];
        $newShift->institution_id = $data['institution_id'];
        $newShift->save();
        return self::getShift($newShift->id);
    }

    public static function updateShift($id, $data)
    {
        Shift::where('id', $id)->update($data);
        return self::getShift($id);
    }

    public static function deleteShift($id)
    {
        return Shift::where('id', $id)->delete();
    }
}
