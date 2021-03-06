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

Route::get('/', 'HomeController@getIndex');
Route::post('/', 'HomeController@getIndex');
Route::get('/earthquakes', 'HomeController@getEarthquakeHistory');
Route::post('/earthquakes', 'HomeController@getEarthquakeHistory');

Route::get('/earthquakes/{id}', 'HomeController@getEarthquakeDetails');

Route::get('/earthquake-heatmap', 'HomeController@getHeatmap');
Route::post('/earthquake-heatmap', 'HomeController@getHeatmap');

Route::get('/earthquake-graphs-charts', 'HomeController@getGraphCharts');
Route::post('/earthquake-graphs-charts', 'HomeController@getGraphCharts');

Route::get('/earthquake-101', 'HomeController@getEarthquake101');

Route::get('/earthquake-hotlines', 'HomeController@getHotlines');

Route::get('/about', 'HomeController@getAbout');

Route::get('/test', 'HomeController@getTest');


Route::prefix('amp')->group(function () {
    Route::get('/', 'HomeController@getIndex')
        ->name('amp-homepage');

    
});