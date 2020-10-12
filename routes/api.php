<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    CityController,
    CountryController,
    InstitutionController,
    PlansController,
    UsersController,
    TeacherController,
    InstitutionYearController,
    TurnController,
    CommissionController
};
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
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/test', [AuthController::class, 'test']);
    Route::group(['middleware' => 'admin'], function () {
        Route::resource('cities', CityController::class);
        Route::resource('countries', CountryController::class);
        Route::resource('institutions', InstitutionController::class);
        Route::resource('plans', PlansController::class);
        Route::resource('users', UsersController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('institutions-years', InstitutionYearController::class);
        Route::resource('turns', TurnController::class);
        Route::resource('commissions', CommissionController::class);
    });
});

Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
