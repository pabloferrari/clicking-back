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
        $new = new Institution();
        $new->name = $data['name']; 
        $new->email = $data['email']; 
        $new->phone = $data['phone']; 
        $new->cuit = $data['cuit']; 
        $new->image = $data['image']; 
        $new->active = $data['active']; 
        $new->plan_id = $data['plan_id']; 
        $new->city_id = $data['city_id']; 
        $new->save();
        return Institution::where('id', $new->id)->with(['plan', 'city.province.country'])->first();
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
