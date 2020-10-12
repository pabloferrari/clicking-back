<?php

namespace App\Classes;

use App\Models\City;

class CityService
{

    public static function getCities()
    {
        return City::with(['province.country'])->get();
    }

    public static function getCity($id)
    {
        return City::where('id', $id)->with(['province.country'])->first();
    }

    public static function createCity($data)
    {
        $new = new City();
        $new->name = $data['name'];
        $new->zip_code = $data['zip_code'];
        $new->province_id = $data['province_id'];
        $new->save();
        return City::where('id', $new->id)->with(['province.country'])->first();
    }

    public static function updateCity($id, $data)
    {
        // City::where('id', $id)->update($data);
        // return City::where('id', $id)->with(['province.country'])->first();
        $province = City::where('id', $id)->with(['province.country'])->first();
        $province->name        = $data['name'];
        $province->zip_code    = $data['zip_code'];
        $province->province_id = $data['province_id'];
        $province->save();
        return $province;
    }

    public static function deleteCity($id)
    {
        return City::where('id', $id)->delete();
    }
}
