<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountryCollection;
use App\Country;
use Validator;
use Str;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::select(['uuid', 'country_code', 'country_name'])->paginate(10);
        return new CountryCollection($countries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_name'      => 'required|max:255',
            'country_code'      => 'required|min:3|max:3|unique:countries',
            'currency_code'     => 'required|min:3|max:3',
            'computation_type'  => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => $validator->messages(),
                'success' => false
            ], 422);
        }

        $country = new Country;
        $country->country_name = $request->country_name;
        $country->country_code = strtoupper($request->country_code);
        $country->uuid = Str::uuid();
        $country->save();

        return [
            'success' => true
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'country_name'      => 'required|max:255',
            'country_code'      => 'required|min:3|max:3',
            'currency_code'     => 'required|min:3|max:3',
            'computation_type'  => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => $validator->messages(),
                'success' => false
            ], 422);
        }

        $country = Country::where('uuid', $uuid)->first();
        if (empty($country)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'Country does not exists.',
                'success' => false
            ], 422);
        }
        $country->country_name = $request->country_name;
        $country->country_code = strtoupper($request->country_code);
        $country->save();

        return [
            'success' => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $country = Country::where('uuid', $uuid)->first();
        if (empty($country)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'Country does not exists.',
                'success' => false
            ], 422);
        }

        $country->delete();
        return [
            'success' => true
        ];
    }
}
