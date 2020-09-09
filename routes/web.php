<?php

use Illuminate\Support\Facades\Route;

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
Route::any('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function() {
    Route::prefix('user')->group(function() {
        Route::post('create', 'ApiController@createUser')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getUsers')->name('get');
        Route::post('edit', 'ApiController@editUser')->name('edit');
        Route::post('delete', 'ApiController@deleteUser')->name('delete');
        Route::post('search', 'ApiController@searchUsers')->name('search');
    });
    Route::prefix('department')->group(function() {
        Route::post('create', 'ApiController@createDepartment')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getDepartments')->name('get');
        Route::post('edit', 'ApiController@editDepartment')->name('edit');
        Route::post('delete', 'ApiController@deleteDepartment')->name('delete');
        Route::post('search', 'ApiController@searchDepartments')->name('search');
    });
    Route::prefix('division')->group(function() {
        Route::post('create', 'ApiController@createDivision')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getDivisions')->name('get');
        Route::post('edit', 'ApiController@editDivision')->name('edit');
        Route::post('delete', 'ApiController@deleteDivision')->name('delete');
        Route::post('search', 'ApiController@searchDivisions')->name('search');
    });
    Route::prefix('country')->group(function() {
        Route::post('create', 'ApiController@createCountry')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getCountries')->name('get');
        Route::post('edit', 'ApiController@editCountry')->name('edit');
        Route::post('delete', 'ApiController@deleteCountry')->name('delete');
        Route::post('search', 'ApiController@searchCountries')->name('search');
        Route::post('getById', 'ApiController@getCountryById')->name('getById');
    });
    Route::prefix('state')->group(function() {
        Route::post('create', 'ApiController@createState')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getStates')->name('get');
        Route::post('edit', 'ApiController@editState')->name('edit');
        Route::post('delete', 'ApiController@deleteState')->name('delete');
        Route::post('search', 'ApiController@searchStates')->name('search');
        Route::post('getById', 'ApiController@getStateById')->name('getById');
    });
    Route::prefix('city')->group(function() {
        Route::post('create', 'ApiController@createCity')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getCities')->name('get');
        Route::post('edit', 'ApiController@editCity')->name('edit');
        Route::post('delete', 'ApiController@deleteCity')->name('delete');
        Route::post('search', 'ApiController@searchCities')->name('search');
        Route::post('getById', 'ApiController@getCityById')->name('getById');
    });
    Route::prefix('employee')->group(function() {
        Route::post('create', 'ApiController@createEmployee')->name('create');
        Route::get('get/{page?}/{pageSize?}', 'ApiController@getEmployees')->name('get');
        Route::post('edit', 'ApiController@editEmployee')->name('edit');
        Route::post('delete', 'ApiController@deleteEmployee')->name('delete');
        Route::post('search', 'ApiController@searchEmployees')->name('search');
    });
});

Route::any('{slug}', function () {
    return view('welcome');
});