<?php

namespace App\Classes;

use App\Models\Province;

class ProvinceService
{

    public static function getProvinces()
    {
        return Province::with(['country'])->get();
    }

    public static function getProvince($id)
    {
        return Province::where('id', $id)->with(['country'])->first();
    }

    public static function createProvince($data)
    {
        $new = new Province();
        $new->name = $data['name'];
        $new->iso31662 = $data['iso31662'];
        $new->country_id = $data['country_id'];
        $new->save();
        return Province::where('id', $new->id)->with(['country'])->first();
    }

    public static function updateProvince($id, $data)
    {
        //$province = Province::where('id', $id)->update($data);
        $province = Province::where('id', $id)->with(['country'])->first();
        $province->name       = $data['name'];
        $province->iso31662   = $data['iso31662'];
        $province->country_id = $data['country_id'];
        $province->save();
        return $province;
        //return Province::where('id', $id)->with(['country'])->first();
    }

    public static function deleteProvince($id)
    {
        return Province::where('id', $id)->delete();
    }
}
