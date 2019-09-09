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

/**
 * @group County management
 *
 * API's for managing the country records
 */
class CountyController extends Controller
{
    /**
     * Display a listing of the counties table from the database.
     *
     * @authenticated
     *
     * @queryParam page int Used for pagination, indicates the current page of the list of record. Example: 1
     *
     * @response {
     *      "data": [
     *          {
     *              "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
     *              "county_name": "Nevada",
     *              "couny_code": "USNVD",
     *          }
     *      ],
     *      "links": {
     *         "first":"\/county?page=1",
     *         "last":"\/county?page=324",
     *         "prev":"\/county?page=1",
     *         "next":"\/county?page=3"
     *      },
     *      "meta": {
     *          "current_page":2,
     *          "from":11,
     *          "last_page":324,
     *          "path":"\/county",
     *          "per_page":10,
     *          "to":20,
     *          "total":3232
     *      }
     * }
     */
    public function index()
    {
        $counties = County::with('country')->with('state')->paginate(10);
        return  CountyCollection::collection($counties);
    }

    /**
     * Store a newly created county in storage.
     *
     * @authenticated
     *
     * @bodyParam county_name string required The name of the county. Example: Nevada
     * @bodyParam county_code string The county's unique code. Example: LSVD
     * @bodyParam country_code string The county's parent country iso 3 code. Example: USA
     * @bodyParam state_code string required The county's parent state iso code. Example: LASVEGAS
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "message" : "Request validation failed",
     *     "errors": [
     *         {
     *             "county_name": [ "County name is required" ]
     *         }
     *     ]
     * }
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
     * Update an existing created county in storage.
     *
     * @authenticated
     *
     * @queryParam uuid string The uuid of the county to be updated. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @bodyParam county_name string required The name of the county. Example: Nevada
     * @bodyParam county_code string The county's unique code. Example: LSVD
     * @bodyParam country_code string The county's parent country iso 3 code. Example: USA
     * @bodyParam state_code string required The county's parent state iso code. Example: LASVEGAS
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "country_name": [ "Country name is required" ]
     *         }
     *     ]
     * }
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
     * Deletes a country in the storage
     *
     * @authenticated
     *
     * @bodyParam uuid string required The uuid of the county to be deleted. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "County does not exists",
     *     "message" : "Request validation failed"
     * }
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

    /**
     * List all the counties using a state code as parameter
     *
     * @authenticated
     *
     * @bodyParam state_code string required The state code of the county's parent state. Example: LVND
     *
     * @response [
     *     {
     *         "county_code": "NVD",
     *         "county_name": "Nevada"
     *     }
     * ]
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "State does not exists",
     *     "message" : "Request validation failed"
     * }
     */
    public function listByStateCode($state_code)
    {
        $state = State::where('state_code', $state_code)->first();

        if(empty($state)) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => 'State does not exists.',
                'success' => false
            ], 422);
        }

        $counties = County::select(['county_code', 'county_name'])->where('state_id', $state->id)->orderBy('county_name', 'asc')->get();

        return $counties;
    }
}
