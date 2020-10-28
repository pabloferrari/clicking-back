<?php

namespace App\Classes;

use App\Models\InstitutionsYear;
use Log;
class InstitutionYearService
{

    public static function getInstitutionsYears()
    {
        return InstitutionsYear::with(['institution'])->get();
    }

    public static function getInstitutionYear($id)
    {
        return  InstitutionsYear::with(['institution'])->findOrFail($id);
    }

    public static function createInstitutionYear($data)
    {
        // Log::debug(__METHOD__ . ' - NEW INSTITUTION YEAR CREATED SERVICE' . json_encode($data));
        $newInstitutionYear = new InstitutionsYear();
        $newInstitutionYear->year = $data['year'];
        $newInstitutionYear->institution_id = $data['institution_id'];
        $newInstitutionYear->save();
        return self::getInstitutionYear($newInstitutionYear->id);
    }

    public static function updateInstitutionYear($id, $data)
    {
        InstitutionsYear::where('id', $id)->update($data);
        return self::getInstitutionYear($id);;
    }

    public static function deleteInstitutionYear($id)
    {
        return InstitutionsYear::where('id', $id)->delete();
    }

}
