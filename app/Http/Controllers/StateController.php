<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\State;

class StateController extends Controller
{
    /**
     * Display a view that displays a list of states
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::paginate('3');
        return view('state.index', [
            'states' => $states
        ]);
    }

    /**
     * Display a view that displays a create form
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $countries = Country::all();
        return view('state.add', [
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

    /**
     * Deletes a state in the database
     *
     * @param string $uuid The uuid of the record to be deleted
     *
     * @return \Illuminate\Http\Response
     */
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
