<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard/{uuid?}', 'HomeController@dashboard')->name('home.dashboard');
Route::get('/stats/states/{uuid}', 'HomeController@states')->name('home.states');

Route::prefix('country')->name('country.')->group(function () {
    Route::get('/', 'CountryController@index')->name('index');
    Route::get('/add', 'CountryController@add')->name('add');
    Route::get('/edit/{uuid}', 'CountryController@edit')->name('edit');
    Route::get('/delete/{uuid}', 'CountryController@delete')->name('delete');
});

Route::prefix('state')->name('state.')->group(function () {
    Route::get('/', 'StateController@index')->name('index');
    Route::get('/add', 'StateController@add')->name('add');
    Route::get('/edit/{uuid}', 'StateController@edit')->name('edit');
    Route::get('/delete/{uuid}', 'StateController@delete')->name('delete');
});

Route::prefix('county')->name('county.')->group(function () {
    Route::get('/', 'CountyController@index')->name('index');
    Route::get('/add', 'CountyController@add')->name('add');
    Route::get('/edit/{uuid}', 'CountyController@edit')->name('edit');
    Route::get('/delete/{uuid}', 'CountyController@delete')->name('delete');
});

Route::prefix('rates')->name('rates.')->group(function () {
    Route::get('/', 'TaxRateController@index')->name('index');
    Route::get('/add', 'TaxRateController@add')->name('add');
    Route::get('/edit/{uuid}', 'TaxRateController@edit')->name('edit');
    Route::get('/delete/{uuid}', 'TaxRateController@delete')->name('delete');
});
