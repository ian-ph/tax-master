<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;
class TaxRateController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('rates.index', [
            'countries' => $countries
        ]);
    }

    public function add()
    {
        $countries = Country::all();
        return view('rates.add', [
            'countries' => $countries
        ]);
    }

    public function edit($uuid)
    {
        $state = State::where('uuid', $uuid)->with('country')->first();
        $countries = Country::all();
        if (empty($state)) {
            abort(404);
        }

        return view('rates.edit', [
            'state' => $state,
            'countries' => $countries
        ]);
    }

    public function delete($uuid)
    {
        $state = State::where('uuid', $uuid)->first();
        if (empty($state)) {
            abort(404);
        }
        return view('rates.delete', [
            'state' => $state
        ]);
    }
}
