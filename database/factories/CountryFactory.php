<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'country_name' => $faker->country,
        'country_code' => $faker->countryISOAlpha3,
        'currency_code' => $faker->currencyCode,
        'computation_type' => rand(1, 2)
    ];
});
