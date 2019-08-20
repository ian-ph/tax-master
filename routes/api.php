<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->name('api.')->group(function(){
    Route::resource('country', 'Api\CountryController');
    Route::resource('county', 'Api\CountyController');
    Route::resource('state', 'Api\StateController');
    Route::get('state/list_by_country_code/{country_code}', 'Api\StateController@listByCountryCode');
});

