<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\County;
use App\Country;
use App\State;

class CountyController extends Controller
{
    public function index()
    {
        return view('county.index');
    }

    public function add()
    {
        $countries = Country::all();
        return view('county.add', [
            'countries' => $countries
        ]);
    }

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
