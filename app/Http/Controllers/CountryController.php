<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\CountryService;
use App\Http\Requests\CountryRequests\CreateCountryRequest;
use App\Http\Requests\CountryRequests\UpdateCountryRequest;
use Log;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = CountryService::getCountries();
        return response()->json(['data' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCountryRequest $request)
    {
        try {

            $newCountry = CountryService::createCountry($request->all());
            Log::debug(__METHOD__ . ' - NEW COUNTRY CREATED ' . json_encode($newCountry));
            return response()->json(['data' => $newCountry]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error creating country"], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = CountryService::getCountry($id);
        return response()->json(['data' => $country]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        try {
            $country = CountryService::updateCountry($id, $request->all());
            Log::debug(__METHOD__ . ' - COUNTRY UPDATED ' . json_encode($country));
            return response()->json(['data' => $country]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . json_encode($request->all()));
            return response()->json(["message" => "Error updating country"], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleted = CountryService::deleteCountry($id);
            Log::debug(__METHOD__ . ' - COUNTRY DELETED id: ' . $id);
            return response()->json(['deleted' => $deleted]);
        } catch (\Throwable $th) {
            Log::error(__METHOD__ . ' - ' . $th->getMessage() . ' - req: ' . $id);
            return response()->json(["message" => "Error deleting country"], 400);
        }
    }
}
