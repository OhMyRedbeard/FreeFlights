<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('freeflights')->group(function() {
        Route::get('/', 'FreeFlightsController@index')->name('freeflights.index');
        Route::post('/create', 'FreeFlightsController@create')->name('freeflights.create');
        Route::get('/generate-flight-number', 'FreeFlightsController@generateFlightNumber')->name('freeflights.generate-flight-number');
    });
});
