<?php

use Illuminate\Http\Request;
use App\Http\Controllers\WaterSensorController;
use App\Http\Controllers\LightSensorController;

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

Route::get('/lastwaterstatus', 'WaterSensorController@getLastWaterState');

Route::get('/lastwater/{limit?}', function (WaterSensorController $controller, $limit = 200) {
    return $controller->getWaterState($limit);
});

Route::get('/lastwaterperdays/{days?}', function (WaterSensorController $controller, $days = 30) {
    return $controller->getWaterStatePerDays($days);
});


Route::get('/lastlightstatus', 'LightSensorControllers@getLastWaterState');

Route::get('/lastlight/{limit?}', function (LightSensorController $controller, $limit = 200) {
    return $controller->getLightState($limit);
});

Route::get('/lastlightperdays/{days?}', function (LightSensorController $controller, $days = 30) {
    return $controller->getLightStatePerDays($days);
});