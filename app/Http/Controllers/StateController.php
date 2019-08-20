<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;

class StateController extends Controller
{
    public function index()
    {
        $states = State::paginate('3');
        return view('state.index', [
            'states' => $states
        ]);
    }

    public function add()
    {
        $countries = Country::all();
        return view('state.add', [
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

        return view('state.edit', [
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
        return view('state.delete', [
            'state' => $state
        ]);
    }
}
