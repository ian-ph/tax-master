<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\County;
use App\Country;
use App\State;

class CountyController extends Controller
{
    /**
     * Display a view that displays a list of counties
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('county.index');
    }

    /**
     * Display a view that displays a create form
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $countries = Country::all();
        return view('county.add', [
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
        $county = County::where('uuid', $uuid)->with('country')->first();

        $countries = Country::all();
        if (empty($county)) {
            abort(404);
        }

        return view('county.edit', [
            'county' => $county,
            'countries' => $countries
        ]);
    }

    /**
     * Deletes a county in the database
     *
     * @param string $uuid The uuid of the record to be deleted
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($uuid)
    {
        $county = County::where('uuid', $uuid)->first();
        if (empty($county)) {
            abort(404);
        }
        return view('county.delete', [
            'county' => $county
        ]);
    }
}
