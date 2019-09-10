<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateCollection;
use App\Country;
use App\State;
use Validator;
use Str;

/**
 * @group State management
 *
 * API's for managing the country records
 */
class StateController extends Controller
{
    /**
     * Display a listing of the states table from the database.
     *
     * @authenticated
     *
     * @queryParam page int Used for pagination, indicates the current page of the list of record. Example: 1
     *
     * @response {
     *      "data": [
     *          {
     *              "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
     *              "state_name": "Nevada",
     *              "state_code": "USNVD",
     *          }
     *      ],
     *      "links": {
     *         "first":"\/state?page=1",
     *         "last":"\/state?page=324",
     *         "prev":"\/state?page=1",
     *         "next":"\/state?page=3"
     *      },
     *      "meta": {
     *          "current_page":2,
     *          "from":11,
     *          "last_page":324,
     *          "path":"\/state",
     *          "per_page":10,
     *          "to":20,
     *          "total":3232
     *      }
     * }
     */
    public function index()
    {
        $countries = State::with('country')->paginate(10);
        return  StateCollection::collection($countries);
    }

    /**
     * Store a newly created state in storage.
     *
     * @authenticated
     *
     * @bodyParam state_name string required The name of the state. Example: Las Vegas
     * @bodyParam state_code string The county's unique code. Example: LS
     * @bodyParam country_code string The county's parent country iso 3 code. Example: USA
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "state_name": [ "State name is required" ]
     *         }
     *     ]
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_name'    => 'required|max:255',
            'state_code'    => 'required|min:2|max:8|unique:states',
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
        $state->uuid = !empty($request->uuid) ? $request->uuid : Str::uuid();
        $state->save();

        return [
            'success' => true
        ];
    }

    /**
     * Updates a newly created state in storage.
     *
     * @authenticated
     *
     * @queryParam uuid string The uuid of the state to be updated. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @bodyParam state_name string required The name of the state. Example: Las Vegas
     * @bodyParam state_code string The county's unique code. Example: LS
     * @bodyParam country_code string The county's parent country iso 3 code. Example: USA
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "state_name": [ "State name is required" ]
     *         }
     *     ]
     * }
     */
    public function update(Request $request, $uuid)
    {
        $state = State::where('uuid', $uuid)->first();
        if (empty($state)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'State does not exists.',
                'success' => false
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'state_name'    => 'required|max:255',
            'state_code'    => 'required|min:2|max:8|unique:states,state_code,' . $state->id,
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

        $state->state_name = $request->state_name;
        $state->state_code = strtoupper($request->state_code);
        $state->country_id = $country->id;
        $state->save();

        return [
            'success' => true
        ];
    }

    /**
     * Deletes a state in the storage
     *
     * @authenticated
     *
     * @bodyParam uuid string required The uuid of the state to be deleted. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "State does not exists",
     *     "message" : "Request validation failed"
     * }
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

    /**
     * List all the states using a country code as parameter
     *
     * @authenticated
     *
     * @bodyParam country_code string required The country code of the county's parent country. Example: USD
     *
     * @response [
     *     {
     *         "county_code": "LVN",
     *         "county_name": "Las Vegas"
     *     }
     * ]
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "Country does not exists",
     *     "message" : "Request validation failed"
     * }
     */
    public function listByCountryCode($country_code)
    {
        $country = Country::where('country_code', $country_code)->first();
        if (empty($country)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'Country does not exists.',
                'success' => false
            ], 422);
        }
        $states = State::select(['state_code', 'state_name'])->where('country_id', $country->id)->orderBy('state_name', 'asc')->get();

        return $states;
    }

    /**
     * List all the states using a country uuid as parameter
     *
     * @authenticated
     *
     * @bodyParam country_uuid string required The country uuid of the county's parent country. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @response [
     *     {
     *         "county_code": "LVN",
     *         "county_name": "Las Vegas"
     *     }
     * ]
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "Country does not exists",
     *     "message" : "Request validation failed"
     * }
     */
    public function listByUuid($country_uuid)
    {
        $country = Country::where('uuid', $country_uuid)->first();
        $country = Country::where('country_code', $country_code)->first();
        if (empty($country)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'Country does not exists.',
                'success' => false
            ], 422);
        }
        $states = State::select(['uuid', 'state_name'])->where('country_id', $country->id)->orderBy('state_name', 'asc')->get();

        return $states;
    }
}
