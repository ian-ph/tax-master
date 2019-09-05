<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateCollection;
use App\Country;
use App\State;
use Validator;
use Str;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = State::with('country')->paginate(10);
        return  StateCollection::collection($countries);
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
            'state_name'    => 'required|max:255',
            'state_code'    => 'required|min:2|max:5|unique:states',
            'country_code'  => 'required',
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
                'message' => 'Request validation failed',
                'errors' => 'Country do not exists',
                'success' => false
            ], 422);
        }

        $state = new State;
        $state->state_name = $request->state_name;
        $state->state_code = strtoupper($request->state_code);
        $state->country_id = $country->id;
        $state->uuid = Str::uuid();
        $state->save();

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
            'state_name'    => 'required|max:255',
            'state_code'    => 'required|min:2|max:5',
            'country_code'  => 'required',
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
                'message' => 'Request validation failed',
                'errors' => 'Country does not exists.',
                'success' => false
            ], 422);
        }
        $state = State::where('uuid', $uuid)->first();
        $state->state_name = $request->state_name;
        $state->state_code = strtoupper($request->state_code);
        $state->country_id = $country->id;
        $state->save();

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
        $state = State::where('uuid', $uuid)->first();
        if (empty($state)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'State does not exists.',
                'success' => false
            ], 422);
        }

        $state->delete();
        return [
            'success' => true
        ];
    }

    public function listByCountryCode($country_code)
    {
        $country = Country::where('country_code', $country_code)->first();
        $states = State::select(['state_code', 'state_name'])->where('country_id', $country->id)->orderBy('state_name', 'asc')->get();

        return $states;
    }

    public function listByUuid($country_uuid)
    {
        $country = Country::where('uuid', $country_uuid)->first();
        $states = State::select(['uuid', 'state_name'])->where('country_id', $country->id)->orderBy('state_name', 'asc')->get();

        return $states;
    }
}
