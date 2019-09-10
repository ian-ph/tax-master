<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\County;
use App\Country;
use App\State;
use App\TaxRate;

class TaxRateController extends Controller
{
    /**
     * Display a view that displays a list of tax rates
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();
        return view('rates.index', [
            'countries' => $countries
        ]);
    }

    /**
     * Display a view that displays a create form
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $countries = Country::all();
        return view('rates.add', [
            'countries' => $countries
        ]);
    }

    /**
     * Display a view that displays a update form
     *
     * @param string $uuid The uuid of the record to be edited
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {

        $taxRate = TaxRate::where('uuid', $uuid)->first();
        if (empty($taxRate)) {
            abort(404);
        }

        $countries  = Country::all();
        $states     = !empty($taxRate->country_id) ? State::where('country_id', $taxRate->country_id)->orderBy('state_name')->get() : [];
        $counties   = !empty($taxRate->state_id) ? County::where('state_id', $taxRate->state_id)->orderBy('county_name')->get() : [];

        return view('rates.edit', [
            'taxRate' => $taxRate,
            'countries' => $countries,
            'states' => $states,
            'counties' => $counties
        ]);
    }

    /**
     * Deletes a tax rate in the database
     *
     * @param string $uuid The uuid of the record to be deleted
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($uuid)
    {
        $taxRate = TaxRate::where('uuid', $uuid)->first();
        if (empty($taxRate)) {
            abort(404);
        }
        return view('rates.delete', [
            'tax_rate' => $taxRate
        ]);
    }
}
