<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\County;
use App\Country;
use App\State;
use App\TaxRate;
use Faker\Generator as Faker;

$factory->define(TaxRate::class, function (Faker $faker) {

    $country    = Country::first();
    $state      = State::where('country_id', $country->id)->first();
    $county     = County::where('country_id', $country->id)->first();

    $minimumBracket = rand(0, 10000);
    $maximumBracket = rand($minimumBracket+10000, $minimumBracket+30000);
    $ratePercentage = rand(0, 35);
    $rateFixed      = rand(25, 150);
    $taxType        = rand(1, 2);
    $taxCategory    = rand(1, 2);
    $note           = Str::random('50');

    return [
        'uuid'                  => $faker->uuid,
        'country_id'            => $country->id,
        'state_id'              => $state->id,
        'county_id'             => null,
        'bracket_minimum'       => $minimumBracket,
        'bracket_maximum'       => $maximumBracket,
        'rate_percentage'       => $ratePercentage,
        'rate_fixed'            => $rateFixed,
        'tax_type'              => $taxType,
        'tax_category'          => $taxCategory,
        'note'                  => $note,
        'implementation_date'   => date('Y-m-d')
    ];
});
