<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountyCollection;
use App\County;
use App\Country;
use App\State;
use Str;
use Validator;

class CountyController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counties = County::with('country')->with('state')->paginate(10);
        return  CountyCollection::collection($counties);
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
            'county_name'   => 'required|max:255',
            'county_code'   => 'required|min:2|max:5|unique:counties',
            'country_code'  => 'required',
            'state_code'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Request validation failed',
                'errors'    => $validator->messages(),
                'success'   => false
            ], 422);
        }

        $country = Country::where('country_code', $request->country_code)->first();
        if (empty($country)) {
            return response()->json([
                'message'   => 'Request validation failed',
                'errors'    => 'Country do not exists',
                'success'   => false
            ], 422);
        }

        $state = State::where('state_code', $request->state_code)->first();
        if (empty($state)) {
            return response()->json([
                'message'   => 'Request validation failed',
                'errors'    => 'State do not exists',
                'success'   => false
            ], 422);
        }

        $county = new County;
        $county->county_name    = $request->county_name;
        $county->county_code    = strtoupper($request->county_code);
        $county->country_id     = $country->id;
        $county->state_id       = $state->id;
        $county->uuid           = Str::uuid();
        $county->save();

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
            'county_name'    => 'required|max:255',
            'county_code'    => 'required|min:2|max:5',
            'country_code'  => 'required',
            'state_code'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => $validator->messages(),
                'success' => false
            ], 422);
        }

        $country = Country::where('country_code', $request->country_code)->first();
        if (empty($country)) {
            return response()->json([
                'message'   => 'Request validation failed',
                'errors'    => 'Country do not exists',
                'success'   => false
            ], 422);
        }

        $state = State::where('state_code', $request->state_code)->first();
        if (empty($state)) {
            return response()->json([
                'message'   => 'Request validation failed',
                'errors'    => 'State do not exists',
                'success'   => false
            ], 422);
        }

        $county = County::where('uuid', $uuid)->first();
        $county->county_name    = $request->county_name;
        $county->county_code    = strtoupper($request->county_code);
        $county->country_id     = $country->id;
        $county->state_id       = $state->id;
        $county->save();

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
        $county = County::where('uuid', $uuid)->first();
        if (empty($county)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'County does not exists.',
                'success' => false
            ], 422);
        }

        $county->delete();
        return [
            'success' => true
        ];
    }

    public function listByStateCode($state_code)
    {
        $state = State::where('state_code', $state_code)->first();
        $counties = County::select(['county_code', 'county_name'])->where('state_id', $state->id)->orderBy('county_name', 'asc')->get();

        return $counties;
    }
}
