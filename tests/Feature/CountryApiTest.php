<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Str;
class CountryApiTest extends TestCase
{

    use WithFaker;

    public function setUp() : void
    {
        parent::setUp();
        $this->setBaseRoute('api.country');
        $this->setBaseModel('App\Country');
    }

    public function testUnauthorizedAccess()
    {
        $response = $this->json('get', route('api.country.index'));
        $response->assertUnauthorized();

        $response = $this->json('post', route('api.country.store'));
        $response->assertUnauthorized();

        $response = $this->json('patch', route('api.country.update', Str::uuid(4)));
        $response->assertUnauthorized();

        $response = $this->json('delete', route('api.country.destroy', Str::uuid(4)));
        $response->assertUnauthorized();
    }


    public function testCreate()
    {
        $this->signIn(null, 'api');
        $this->create();
    }

    public function testUpdate()
    {
        $this->signIn(null, 'api');
        $this->update([
            'currency_code' => $this->faker->currencyCode
        ]);
    }

    public function testDelete()
    {
        $this->signIn(null, 'api');
        $this->destroy();
    }
}
