<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
{
    /**
     * Display a view that displays a list of countries
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::paginate('3');
        return view('country.index', [
            'countries' => $countries
        ]);
    }

    /**
     * Display a view that displays a create form
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('country.add');
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
        $country = Country::where('uuid', $uuid)->first();
        if (empty($country)) {
            abort(404);
        }
        return view('country.edit', [
            'country' => $country
        ]);
    }

    /**
     * Deletes a country in the database
     *
     * @param string $uuid The uuid of the record to be deleted
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($uuid)
    {
        $country = Country::where('uuid', $uuid)->first();
        if (empty($country)) {
            abort(404);
        }
        return view('country.delete', [
            'country' => $country
        ]);
    }
}
