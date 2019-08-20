<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::paginate('3');
        return view('country.index', [
            'countries' => $countries
        ]);
    }

    public function add()
    {
        return view('country.add');
    }

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
