<?php

namespace App\Classes;

use App\Models\Country;

class CountryService
{

    public static function getCountries()
    {
        return Country::with(['provinces.cities'])->get();
    }

    public static function getCountry($id)
    {
        return Country::with(['provinces.cities'])->where('id', $id)->first();
    }

    public static function createCountry($data)
    {
        $newCountry = new Country();
        $newCountry->name = $data['name'];
        $newCountry->code = $data['code'];
        $newCountry->save();
        return Country::with(['provinces.cities'])->where('id', $newCountry->id)->first();
    }

    public static function updateCountry($id, $data)
    {
        $country = Country::where('id', $id)->with(['provinces.cities'])->first();
        $country->name = $data['name'];
        $country->code = $data['code'];
        $country->save();
        return $country;
    }

    public static function deleteCountry($id)
    {
        return Country::where('id', $id)->delete();
    }
}
