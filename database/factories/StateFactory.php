<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Country;
use App\State;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    $country = Country::first();

    return [
        'uuid' => $faker->uuid,
        'state_name' => $faker->state,
        'state_code' => $faker->stateAbbr,
        'country_id' => $country->id
    ];
});
