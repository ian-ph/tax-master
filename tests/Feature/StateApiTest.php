<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Country;
use App\State;
use Str;

class StateApiTest extends TestCase
{

    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        $this->setBaseRoute('api.state');
        $this->setBaseModel('App\State');
    }

    public function testUnauthorizedAccess()
    {
        $response = $this->json('get', route('api.state.index'));
        $response->assertUnauthorized();

        $response = $this->json('post', route('api.state.store'));
        $response->assertUnauthorized();

        $response = $this->json('patch', route('api.state.update', Str::uuid(4)));
        $response->assertUnauthorized();

        $response = $this->json('delete', route('api.state.destroy', Str::uuid(4)));
        $response->assertUnauthorized();
    }


    public function testCreate()
    {
        create('App\Country');

        $country = Country::first();

        $this->signIn(null, 'api');
        $this->create([
            'state_code' => Str::random(5),
            'state_name' => $this->faker->state,
            'country_code' => $country->country_code,
        ]);
    }

    public function testUpdate()
    {
        create('App\Country');

        $country = Country::first();

        $this->signIn(null, 'api');
        $this->update([
            'state_code' => Str::random(5),
            'state_name' => $this->faker->state,
        ]);
    }

    public function testDelete()
    {
        create('App\Country');

        $this->signIn(null, 'api');
        $this->destroy();
    }
}
