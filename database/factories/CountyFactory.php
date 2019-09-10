<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\County;
use App\Country;
use App\State;
use Faker\Generator as Faker;

$factory->define(County::class, function (Faker $faker) {

    $country = Country::first();
    $state = State::where('country_id', $country->id)->first();

    return [
        'uuid' => $faker->uuid,
        'county_name' => $faker->city,
        'county_code' => Str::random(6),
        'country_id' =>  $country->id,
        'state_id'  => $state->id
    ];
});
