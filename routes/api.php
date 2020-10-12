<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    CityController,
    ProvinceController,
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

    Route::group(['middleware' => 'admin'], function () {


        Route::get('/testAdmin', [AuthController::class, 'test']);

        Route::resource('countries', CountryController::class);
        Route::resource('provinces', ProvinceController::class);
        Route::resource('cities', CityController::class);
        Route::resource('institutions', InstitutionController::class);
        Route::resource('plans', PlansController::class);
        Route::resource('users', UsersController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('institutions-years', InstitutionYearController::class);
        Route::resource('turns', TurnController::class);
        Route::resource('commissions', CommissionController::class);
    });


    Route::group(['middleware' => 'institution'], function () {

        Route::get('/testInstitution', [AuthController::class, 'test']);

    });


    Route::group(['middleware' => 'teacher'], function () {
    });

    Route::group(['middleware' => 'student'], function () {
    });


    Route::group(['middleware' => 'institution'], function () {

        Route::get('/testInstitution', [AuthController::class, 'test']);

    });


    Route::group(['middleware' => 'teacher'], function () {
    });

    Route::group(['middleware' => 'student'], function () {
    });


    Route::group(['middleware' => 'institution'], function () {

        Route::get('/testInstitution', [AuthController::class, 'test']);

    });


    Route::group(['middleware' => 'teacher'], function () {
    });

    Route::group(['middleware' => 'student'], function () {
    });


    Route::group(['middleware' => 'institution'], function () {

        Route::get('/testInstitution', [AuthController::class, 'test']);

    });


    Route::group(['middleware' => 'teacher'], function () {
    });

    Route::group(['middleware' => 'student'], function () {
    });
});

// Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
