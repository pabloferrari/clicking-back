<?php

namespace App\Classes;

use App\Models\Institution;

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
        $newInstitution = new Institution();
        $newInstitution->name = $data['name']; 
        $newInstitution->email = $data['email']; 
        $newInstitution->phone = $data['phone']; 
        $newInstitution->cuit = $data['cuit'] ?? null; 
        $newInstitution->image = $data['image'] ?? null; 
        $newInstitution->active = $data['active'] ?? null; 
        $newInstitution->plan_id = $data['plan_id'] ?? null; 
        $newInstitution->city_id = $data['city_id'] ?? null; 
        $newInstitution->active = true; 
        $newInstitution->save();
        return $newInstitution;
    }

    public static function updateInstitution($id, $data)
    {
        Institution::where('id', $id)->update($data);
        return Institution::where('id', $id)->with(['plan', 'city.province.country'])->first();
    }

    public static function deleteInstitution($id)
    {
        return Institution::where('id', $id)->delete();
    }
}
