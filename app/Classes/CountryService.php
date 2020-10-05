<?php

namespace App\Classes;

use App\Models\Country;

class CountryService
{

    public static function getCountries()
    {
        return Country::with(['province.cities'])->get();
    }

    public static function getCountry($id)
    {
        return Country::with(['province.cities'])->where('id', $id)->first();
    }

    public static function createCountry($data)
    {
        $newCountry = new Country();
        $newCountry->name = $data['name'];
        $newCountry->code = $data['code'];
        $newCountry->save();
        return Country::with(['province.cities'])->where('id', $newCountry->id)->first();
    }

    public static function updateCountry($id, $data)
    {
        Country::where('id', $id)->update($data);
        return Country::where('id', $id)->with(['province.cities'])->first();
    }

    public static function deleteCountry($id)
    {
        return Country::where('id', $id)->delete();
    }
}
