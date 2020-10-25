<?php

namespace App\Classes;

use App\Models\Commission;

class CommissionService
{

    public static function getCommissions()
    {
        return Commission::with(['turn','institution_year'])->get();
    }

    public static function getCommission($id)
    {
        return  Commission::with(['turn','institution_year'])->find($id);
    }

    public static function createCommision($data)
    {
        $newCommission = new Commission();
        $newCommission->name                = $data['name'];
        $newCommission->turn_id             = $data['turn_id'];
        $newCommission->institution_year_id = $data['institution_year_id'];
        $newCommission->save();
        return self::getCommission($newCommission->id);
    }

    public static function updateCommission($id, $data)
    {
        Commission::where('id', $id)->update($data);
        return self::getCommission($id);;
    }

    public static function deleteCommission($id)
    {
        return Commission::where('id', $id)->delete();
    }

}
