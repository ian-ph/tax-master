<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
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


    public function test_create()
    {
        create('App\Country');
        create('App\State');
        $this->signIn(null, 'api');
        $this->create();
    }

    public function test_update()
    {
        create('App\Country');
        create('App\State');
        $this->signIn(null, 'api');
        $this->update([
            'county_code' => $this->faker->postcode
        ]);
    }

    public function test_delete()
    {
        create('App\Country');
        create('App\State');
        $this->signIn(null, 'api');
        $this->destroy();
    }
}
