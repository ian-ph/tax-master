<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;

class SetupController extends Controller
{
    public function quickSetup()
    {
        $countries = Countries::where('name.common', 'United Kingdom')->first()->hydrate('states');

        return view('setup.quick_setup', [
            'countries' => $countries
        ]);
    }
}
