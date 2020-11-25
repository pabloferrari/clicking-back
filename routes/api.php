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
    StudentController,
    InstitutionYearController,
    SubjectController,
    CourseTypeController,
    ClassroomController,
    ShiftController,
    CommissionController,
    CourseClassController,
    AssignmentTypeController,
    AssignmentController,
    AssignmentGroupController,
    CourseController,
    ClassroomStudentController
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

    Route::group(['middleware' => 'role:institution,teacher,admin'], function () {
        Route::get('/testAdmin', [AuthController::class, 'test']);
        Route::resource('classrooms', ClassroomController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('classes', CourseClassController::class);
    });


    Route::group(['middleware' => 'role:teacher,student'], function () {

        Route::get('my-courses', [CourseController::class, 'myCourses']);
        // Route::resource('courses', CourseController::class);
        // Route::resource('classes', CourseClassController::class);
    });

    Route::get('profile', [UsersController::class, 'getProfile']);
    Route::put('profile', [UsersController::class, 'updateProfile']);
    Route::put('profile/reset-password', [UsersController::class, 'resetPassword']);

    Route::get('courses/byClassroom/{id}', [CourseController::class, 'coursesByClassroom']);

    Route::group(['middleware' => 'admin'], function () {
        // Route::get('/testAdmin', [AuthController::class, 'test']);
        Route::resource('countries', CountryController::class);
        Route::resource('provinces', ProvinceController::class);
        Route::resource('cities', CityController::class);
        Route::resource('institutions', InstitutionController::class);
        Route::resource('plans', PlansController::class);
        Route::resource('users', UsersController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('students', StudentController::class);
        Route::resource('shifts', ShiftController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('course-types', CourseTypeController::class);
        // Route::resource('classrooms', ClassroomController::class);
        // Route::resource('courses', CourseController::class);
        Route::resource('classroom-students', ClassroomStudentController::class);
        // Route::resource('classes', CourseClassController::class);
        Route::resource('assignment-types', AssignmentTypeController::class);
        Route::resource('assignments', AssignmentController::class);
        Route::resource('assignment-groups', AssignmentGroupController::class);
    });


    Route::group(['middleware' => 'institution'], function () {
        Route::get('/testInstitution', [AuthController::class, 'test']);

        Route::get('institutions/{id}/dashboard', [InstitutionController::class, 'institutionCount']);

        Route::get('classrooms/{id}/dashboard', [ClassroomController::class, 'classroomCount']);
        Route::get('courses/classes/{id}/dashboard', [CourseController::class, 'courseClassesCount']);


        // Route::resource('users', UsersController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('students', StudentController::class);

        Route::resource('shifts', ShiftController::class);
        // Route::resource('classrooms', ClassroomController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('course-types', CourseTypeController::class);
        // Route::resource('classes', CourseClassController::class);
        Route::resource('assignment-types', AssignmentTypeController::class);
        Route::resource('assignments', AssignmentController::class);
        Route::resource('assignment-groups', AssignmentGroupController::class);
        // Route::resource('courses', CourseController::class);


        Route::resource('classroom-students', ClassroomStudentController::class);
    });


    Route::group(['middleware' => 'teacher'], function () {
        // Route::resource('courses', CourseController::class);
        // Route::resource('classes', CourseClassController::class);
    });

    Route::group(['middleware' => 'student'], function () {
    });
});

// Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

// Route::get('*', function () {
// 	return response()->json(['name' => "Clicking Api", 'version' => 0.1]);
// });

Route::get('/{any}', function ($any) {
    return response()->json(['name' => "Clicking Api", 'version' => 0.1, 'path' => "/$any"]);
})->where('any', '.*');
