<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountryCollection;
use App\Country;
use Validator;
use Str;

/**
 * @group Country management
 *
 * API's for managing the country records
 */
class CountryController extends Controller
{
    /**
     * Display a listing of the countries table from the database.
     *
     * @authenticated
     *
     * @queryParam page int Used for pagination, indicates the current page of the list of record. Example: 1
     *
     * @response {
     *      "data": [
     *          {
     *              "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
     *              "country_code": "USA",
     *              "country_name": "United States",
     *              "currency_code": "USD",
     *              "computation_type": 1
     *          }
     *      ],
     *      "links": {
     *         "first":"\/country?page=1",
     *         "last":"\/country?page=324",
     *         "prev":"\/country?page=1",
     *         "next":"\/country?page=3"
     *      },
     *      "meta": {
     *          "current_page":2,
     *          "from":11,
     *          "last_page":324,
     *          "path":"\/country",
     *          "per_page":10,
     *          "to":20,
     *          "total":3232
     *      }
     * }
     */
    public function index()
    {
        $countries = Country::select(['uuid', 'country_code', 'country_name'])->paginate(10);
        return new CountryCollection($countries);
    }

    /**
     * Store a newly created country in storage.
     *
     * @authenticated
     *
     * @bodyParam country_name string required The name of the country. Example: United States
     * @bodyParam country_code string The country's iso 3 code. Example: USA
     * @bodyParam currency_code string The country's iso 3 currency code. Example: USD
     * @bodyParam computation_type int required The type of tax computation the country is using. Example: 1
     *
     * @response 402 {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" [
     *         {
     *             "country_name": [ "Country name is required" ]
     *         }
     *     ]
     * }
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
     * Update the specified country in storage.
     *
     * @authenticated
     *
     * @queryParam uuid string The uuid of the country to be updated. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @bodyParam country_name string required The name of the country. Example: United States
     * @bodyParam country_code string The country's iso 3 code. Example: USA
     * @bodyParam currency_code string The country's iso 3 currency code. Example: USD
     * @bodyParam computation_type int required The type of tax computation the country is using. Example: 1
     *
     * @response 402 {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" [
     *         {
     *             "country_name": [ "Country name is required" ]
     *         }
     *     ],
     *     "message": "Request validation failed"
     * }
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
     * Deletes a country in the storage
     *
     * @authenticated
     *
     * @bodyParam uuid string required The uuid of the country to be deleted. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @response 402 {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "Country does not exists"
     *     "message" : "Request validation failed"
     * }
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
