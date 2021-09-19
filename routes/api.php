<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\api\v1\RegisterController;
use App\Http\Controllers\api\v1\AirportsController;
use App\Http\Controllers\api\v1\RoutesController;
use App\Http\Controllers\api\v1\CitiesController;
use App\Http\Controllers\api\v1\CommentsController;
use App\Http\Controllers\api\v1\FlightsController;

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

Route::post('register', [RegisterController::class, 'store']);

Route::middleware(['auth.basic.once'])->group(function () {

    Route::middleware(['role:administrator'])->group(function() {
        Route::post('airports/import', [AirportsController::class, 'import']);
        Route::post('routes/import', [RoutesController::class, 'import']);
        Route::post('cities', [CitiesController::class, 'store']);
    });

    Route::get('cities', [CitiesController::class, 'getSearchedCitiesWithComments']);
    Route::apiResource('comments', CommentsController::class)->only('store', 'update', 'destroy');

    Route::get('cheapest-flight', [FlightsController::class, 'cheapestFlight']);

});
