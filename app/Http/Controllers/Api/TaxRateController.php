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

class TaxRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

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
