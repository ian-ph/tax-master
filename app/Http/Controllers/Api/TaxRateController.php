<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaxRateCollection;
use App\TaxRate;
use App\Country;
use App\State;
use Validator;
use Propaganistas\LaravelIntl\Facades\Currency as Currency;
use Propaganistas\LaravelIntl\Facades\Number as Number;
use Str;

/**
 * @group Tax Rates management
 *
 * API's for managing the tax rates records
 */
class TaxRateController extends Controller
{
    /**
     * Display a listing of the tax_rates table from the database.
     *
     * @authenticated
     *
     * @bodyParam country string The uuid of the country of the tax rate, to be used a filtering of the listing.
     * @bodyParam state string The uuid of the state of the tax rate, to be used a filtering of the listing.
     *
     * @response {
     *      "data": [
     *          {
     *              "uuid": "7b7009a8-cf1b-4466-84a1-8051b34a58b2",
     *              "country": "United States",
     *              "state": "Las Vegas",
     *              "county": "N/A",
     *              "income_bracket": "$0 - $1,500",
     *              "rate": "2%",
     *              "note": "Example note"
     *          }
     *      ],
     *      "links": {
     *         "first":"\/tax-rate?page=1",
     *         "last":"\/tax-rate?page=324",
     *         "prev":"\/tax-rate?page=1",
     *         "next":"\/tax-rate?page=3"
     *      },
     *      "meta": {
     *          "current_page":2,
     *          "from":11,
     *          "last_page":324,
     *          "path":"\/tax-rate",
     *          "per_page":10,
     *          "to":20,
     *          "total":3232
     *      }
     * }
     */
    public function index(Request $request)
    {
        $rates = TaxRate::with('country')->with('state');

        if (!empty($request->country)) {
            $country = Country::where('uuid', $request->country)->first();
            if (empty($country)) {
                return response()->json([
                    'message' => 'Invalid filter parameter',
                    'success' => false
                ], 422);
            }

            $rates = $rates->where('country_id', $country->id);
        }

        if (!empty($request->state)) {
            $state = State::where('uuid', $request->state)->first();
            if (empty($state)) {
                return response()->json([
                    'message' => 'Invalid filter parameter',
                    'success' => false
                ], 422);
            }

            $rates = $rates->where('state_id', $state->id);
        }

        $rates = $rates->paginate(10);
        return  TaxRateCollection::collection($rates);
    }


    /**
     * Store a newly created tax rate in storage.
     *
     * @authenticated
     *
     * @bodyParam country_code string required The country code for this tax rate. Example: USA
     * @bodyParam state_code string The state code of this tax rate. Example: LSV
     * @bodyParam county_code string The county code of this tax rate. Example: NVD
     * @bodyParam implementation_date string required The date on which this tax rate will become effective.
     * @bodyParam rate_percentage float The rate is percentage. Example: 15.5
     * @bodyParam rate_fixed float The rate in fixed amount. Example: 250.50
     * @bodyParam bracket_minimum float required The minimum bracket for this tax rate.
     * @bodyParam bracket_maximum The maximum bracket for this tax rate
     * @bodyParam tax_type int required The type of this tax, indicated 1 if single, 2 for joint.
     * @bodyParam note string required Descriptive note for this tax rate
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "country_code": [ "country code is required" ]
     *         }
     *     ]
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code'          => 'required|exists:countries,country_code',
            'state_code'            => 'nullable|exists:states,state_code',
            'county_code'           => 'nullable|exists:county,county_code',
            'implementation_date'   => 'required|date',
            'rate_percentage'       => 'required|numeric|max:99.99',
            'rate_fixed'            => 'required|numeric',
            'bracket_minimum'       => 'numeric',
            'bracket_maximum'       => 'nullable|numeric|gt:bracket_minimum',
            'tax_type'              => 'required|in:1,2',
            'note'                  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => $validator->messages(),
                'success' => false
            ], 422);
        }

        $country    = Country::where('country_code', $request->country_code)->first()->id;
        $state      = !empty($request->state_code) ? State::where('country_code', $request->state_code)->first()->id : null;
        $county     = !empty($request->county_code) ? County::where('country_code', $request->county_code)->first()->id : null;

        $taxRate                        = new TaxRate;
        $taxRate->uuid                  = Str::uuid();
        $taxRate->country_id            = $country;
        $taxRate->state_id              = $state;
        $taxRate->county_id             = $county;
        $taxRate->implementation_date   = $request->implementation_date;
        $taxRate->rate_fixed            = $request->rate_fixed;
        $taxRate->rate_percentage       = $request->rate_percentage;
        $taxRate->tax_type              = $request->tax_type;
        $taxRate->bracket_minimum       = $request->bracket_minimum;
        $taxRate->bracket_maximum       = $request->bracket_maximum;
        $taxRate->note                  = $request->note;
        $taxRate->save();
    }

    /**
     * Store a newly created tax rate in storage.
     *
     * @authenticated
     *
     * @queryParam uuid string The uuid of the tax rate to be updated. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @bodyParam country_code string required The country code for this tax rate. Example: USA
     * @bodyParam state_code string The state code of this tax rate. Example: LSV
     * @bodyParam county_code string The county code of this tax rate. Example: NVD
     * @bodyParam implementation_date string required The date on which this tax rate will become effective.
     * @bodyParam rate_percentage float The rate is percentage. Example: 15.5
     * @bodyParam rate_fixed float The rate in fixed amount. Example: 250.50
     * @bodyParam bracket_minimum float required The minimum bracket for this tax rate.
     * @bodyParam bracket_maximum The maximum bracket for this tax rate
     * @bodyParam tax_type int required The type of this tax, indicated 1 if single, 2 for joint.
     * @bodyParam note string required Descriptive note for this tax rate
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "country_code": [ "country code is required" ]
     *         }
     *     ]
     * }
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Deletes a tax_rate in the storage
     *
     * @authenticated
     *
     * @bodyParam uuid string required The uuid of the tax_rate to be deleted. Example: 7b7009a8-cf1b-4466-84a1-8051b34a58b2
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors" : "Tax rate does not exists",
     *     "message" : "Request validation failed"
     * }
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Generate a easily readable preview of the tax rate
     *
     * @authenticated
     *
     * @bodyParam country_code string required The country code and will be used as the reference for the currency
     * @bodyParam rate_percentage float The tax rate in percentage
     * @bodyParam rate_fixed float The tax rate in fixed amount
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "country_code": [ "country code is required" ]
     *         }
     *     ]
     * }
     */
    public function ratePreview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code'      => 'required|exists:countries,country_code',
            'rate_percentage'   => 'nullable|numeric|max:99.99',
            'rate_fixed'        => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => $validator->messages(),
                'success' => false
            ], 422);
        }

        $country = Country::where('country_code', $request->country_code)->first();
        $result = '';

        if (!empty($request->rate_fixed)) {
            $result .= Currency::format($request->rate_fixed, $country->currency_code);
        }

        if (!empty($request->rate_percentage)) {
            if (!empty($result)) {
                $result .= ' + ';
            }
            $result .= Number::percent($request->rate_percentage / 100);
        }
        if (empty($result)) {
            $result = "Not Set";
        }
        return [
            'result' => $result
        ];
    }

    /**
     * Generate a easily readable preview of the tax rate
     *
     * @authenticated
     *
     * @bodyParam country_code string required The country code and will be used as the reference for the currency
     * @bodyParam bracket_minimum float The minimum bracket of the tax rate
     * @bodyParam bracket_maximum float The maximum bracket of the tax rate
     *
     * @response {
     *     "success" : true
     * }
     *
     * @response 422 {
     *     "success" : false,
     *     "errors": [
     *         {
     *             "country_code": [ "country code is required" ]
     *         }
     *     ]
     * }
     */
    public function bracketPreview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code'    => 'required|exists:countries,country_code',
            'bracket_minimum' => 'nullable|numeric',
            'bracket_maximum' => 'nullable|numeric|gt:bracket_minimum',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Request validation failed',
                'errors' => $validator->messages(),
                'success' => false
            ], 422);
        }

        $country = Country::where('country_code', $request->country_code)->first();
        $result = '';

        if (empty($request->bracket_maximum)) {
            $result = Currency::format($request->bracket_minimum, $country->currency_code) . ' and above.';
        } else {
            $result = Currency::format($request->bracket_minimum, $country->currency_code) . ' to ' . Currency::format($request->bracket_maximum, $country->currency_code);
        }

        return [
            'result' => $result
        ];
    }
}
