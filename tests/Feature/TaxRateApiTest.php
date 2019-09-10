<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Country;
use App\State;
use App\County;
use Str;

class TaxRateApiTest extends TestCase
{

    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        $this->setBaseRoute('api.tax-rate');
        $this->setBaseModel('App\TaxRate');
    }

    public function testUnauthorizedAccess()
    {
        $response = $this->json('get', route('api.tax-rate.index'));
        $response->assertUnauthorized();

        $response = $this->json('post', route('api.tax-rate.store'));
        $response->assertUnauthorized();

        $response = $this->json('patch', route('api.tax-rate.update', Str::uuid(4)));
        $response->assertUnauthorized();

        $response = $this->json('delete', route('api.tax-rate.destroy', Str::uuid(4)));
        $response->assertUnauthorized();
    }


    public function testCreate()
    {
        create('App\Country');
        create('App\State');
        create('App\County');

        $country = Country::first();
        $state = State::first();
        $county = County::first();

        $minimumBracket = rand(0, 10000);
        $maximumBracket = rand($minimumBracket+10000, $minimumBracket+30000);
        $ratePercentage = rand(0, 35);
        $rateFixed      = rand(25, 150);
        $taxType        = rand(1, 2);
        $taxCategory    = rand(1, 2);
        $note           = Str::random('50');

        $this->signIn(null, 'api');
        $this->create([
            'uuid'                  => $this->faker->uuid,
            'country_code'            => $country->country_code,
            'state_code'              => $state->state_code,
            'county_code'             => null,
            'bracket_minimum'       => $minimumBracket,
            'bracket_maximum'       => $maximumBracket,
            'rate_percentage'       => $ratePercentage,
            'rate_fixed'            => $rateFixed,
            'tax_type'              => $taxType,
            'tax_category'          => $taxCategory,
            'note'                  => $note,
            'implementation_date'   => date('Y-m-d')
        ]);
    }

    public function testUpdate()
    {
        create('App\Country');
        create('App\State');
        create('App\County');

        $minimumBracket = rand(10000, 20000);
        $maximumBracket = rand($minimumBracket+10000, $minimumBracket+30000);

        $this->signIn(null, 'api');
        $this->update([
            'bracket_minimum'       => $minimumBracket,
            'bracket_maximum'       => $maximumBracket,
        ]);
    }

    public function testDelete()
    {
        create('App\Country');
        create('App\State');
        create('App\County');

        $this->signIn(null, 'api');
        $this->destroy();
    }
}
