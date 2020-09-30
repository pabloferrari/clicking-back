<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{AuthController, PlansController, CountryController};
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

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', [AuthController::class, 'login']);
    
    Route::group(['middleware' => 'auth:api'], function() {

        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);

    });

});

Route::group(['middleware' => 'auth:api'], function() {

    Route::get('/test', [AuthController::class, 'test']);

    Route::group(['middleware' => 'admin'], function() {

        Route::resource('plans', PlansController::class);
        Route::resource('countries', CountryController::class);

    });

});

Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');