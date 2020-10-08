<?php

namespace App\Classes;

use App\Models\Institution;

use App\Classes\Helpers;

class InstitutionService
{

    public static function getInstitutions()
    {
        return Institution::with(['plan', 'city.province.country'])->get();
    }

    public static function getInstitution($id)
    {
        return Institution::where('id', $id)->with(['plan', 'city.province.country'])->first();
    }

    public static function createInstitution($data)
    {
        $params = Helpers::paramBuilder('Institution', $data);
        $newInstitution = Institution::create($params);
        return $newInstitution;
    }

    public static function updateInstitution($id, $data)
    {
        $params = Helpers::paramBuilder('Institution', $data);
        Institution::where('id', $id)->update($params);
        return Institution::where('id', $id)->with(['plan', 'city.province.country'])->first();
    }

    public static function deleteInstitution($id)
    {
        return Institution::where('id', $id)->delete();
    }
}
