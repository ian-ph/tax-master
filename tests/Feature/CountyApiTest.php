<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Country;
use App\State;
use Str;

class CountyApiTest extends TestCase
{

    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        $this->setBaseRoute('api.county');
        $this->setBaseModel('App\County');
    }

    public function testUnauthorizedAccess()
    {
        $response = $this->json('get', route('api.county.index'));
        $response->assertUnauthorized();

        $response = $this->json('post', route('api.county.store'));
        $response->assertUnauthorized();

        $response = $this->json('patch', route('api.county.update', Str::uuid(4)));
        $response->assertUnauthorized();

        $response = $this->json('delete', route('api.county.destroy', Str::uuid(4)));
        $response->assertUnauthorized();
    }


    public function testCreate()
    {
        create('App\Country');
        create('App\State');

        $country = Country::first();
        $state = State::first();

        $this->signIn(null, 'api');
        $this->create([
            'county_code' => Str::random(5),
            'county_name' => $this->faker->city,
            'country_code' => $country->country_code,
            'state_code' => $state->state_code
        ]);
    }

    public function testUpdate()
    {
        create('App\Country');
        create('App\State');

        $country = Country::first();
        $state = State::first();

        $this->signIn(null, 'api');
        $this->update([
            'county_code' => Str::random(5),
            'county_name' => $this->faker->city,
        ]);
    }

    public function testDelete()
    {
        create('App\Country');
        create('App\State');
        $this->signIn(null, 'api');
        $this->destroy();
    }
}
