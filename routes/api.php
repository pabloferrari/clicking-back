<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    BigBlueButtonController,
    CityController,
    ProvinceController,
    CountryController,
    InstitutionController,
    PlansController,
    UsersController,
    TeacherController,
    StudentController,
    ImageController,
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
    NotificationsController,
    CourseController,
    ClassroomStudentController,
    NewsController,
    CommentController,
    LibrariesController,
    TicketsController,
    EventsController,
    FolderController
};
use App\Models\Assignment;
use App\Models\Folders;

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

    Route::get('profile', [UsersController::class, 'getProfile']);
    Route::get('notifications', [NotificationsController::class, 'getNotifications']);
    Route::post('tickets', [TicketsController::class, 'create']);
    Route::get('event-types', [EventsController::class, 'getEventTypes']);
    Route::get('events', [EventsController::class, 'index']);
    Route::get('events/{id}', [EventsController::class, 'getEvent']);
    Route::post('events', [EventsController::class, 'create']);

    Route::put('profile', [UsersController::class, 'updateProfile']);
    Route::put('profile/reset-password', [UsersController::class, 'resetPassword']);

    Route::get('courses/byClassroom/{id}', [CourseController::class, 'coursesByClassroom']);

    // FIND USERS BY INSTITUTION AND FILTER
    Route::get('get-users-institution/{filter}', [UsersController::class, 'getUsersInstitution']);
    Route::get('bigbluebutton/get-meeting-types', [BigBlueButtonController::class, 'getMeetingTypes']);



    Route::group(['middleware' => 'role:institution,teacher,admin,root,student'], function () {
        Route::get('/testAdmin', [AuthController::class, 'test']);
        Route::resource('classrooms', ClassroomController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('classes', CourseClassController::class);

        Route::get('classes/assignments/{id}/dashboard', [CourseClassController::class, 'classAssignmentCount']);

        Route::get('classes/course/{id}/students', [CourseClassController::class, 'courseClassByStudents']);
        Route::get('assignments/course/{id}', [AssignmentController::class, 'assignmentByCourse']);

        Route::resource('news', NewsController::class);
        // ----- comments Routes --- //
        Route::get('comments/byCourse/{id}', [CommentController::class, 'commentByCourse']);
        Route::get('comments/byAssignment/{id}/{user_id?}', [CommentController::class, 'commentByAssignment']);
        Route::resource('comments', CommentController::class);

        Route::resource('teachers', TeacherController::class);
        Route::resource('students', StudentController::class);
        // ------------ BigBlueButton Routes ------------ //
        Route::prefix('bigbluebutton')->group(function () {

            Route::post('create-meeting', [BigBlueButtonController::class, 'createMeeting']);
            Route::post('end-meeting', [BigBlueButtonController::class, 'endMeeting']);
            Route::post('join-as-moderator', [BigBlueButtonController::class, 'joinAsModerator']);
        });
    });


    Route::group(['middleware' => 'role:teacher,student'], function () {

        Route::get('my-courses', [CourseController::class, 'myCourses']);
        Route::get('my-assignments/{id}/{status}', [AssignmentController::class, 'myAssignments']);
        Route::get('my-courses-assignments-count', [CourseController::class, 'myCoursesAssignmentsCount']);
        Route::get('assignments/detail/{id}', [AssignmentController::class, 'assignmentDetail']);
        Route::get('assignments/detail/student/{id}', [AssignmentController::class, 'assignmentStudentDetailById']);
        Route::get('folders/byCourse/{id}', [FolderController::class, 'folderByCourse']);
        // Route::resource('classes', CourseClassController::class);


        Route::post('assignment-students', [AssignmentController::class, 'storeAssignmentStudent']);
        Route::resource('libraries', LibrariesController::class);

        Route::get('assignments/file-teacher/{id}/{userId}', [AssignmentController::class, 'getAssignmentTeacher']);
        Route::get('assignments/file-student/{id}/{userId}', [AssignmentController::class, 'getAssignmentStudent']);
        Route::resource('folders', FolderController::class);

        Route::delete('assignments/delete-file-student/{id}/{assignment_id}/{user_id}', [AssignmentController::class, 'deleteFileStudent']);
    });

    Route::group(['middleware' => 'admin'], function () {

        Route::get('tickets', [TicketsController::class, 'index']);
        Route::get('tickets/{id}', [TicketsController::class, 'getTicket']);

        Route::resource('countries', CountryController::class);
        Route::resource('provinces', ProvinceController::class);
        Route::resource('cities', CityController::class);
        Route::resource('institutions', InstitutionController::class);
        Route::resource('plans', PlansController::class);
        Route::resource('users', UsersController::class);

        // Route::resource('teachers', TeacherController::class);
        Route::get('teachers/byInstitution/{id}', [TeacherController::class, 'teacherByInstitution']);

        // Route::resource('students', StudentController::class);
        Route::get('students/byInstitution/{id}', [StudentController::class, 'studentsByInstitution']);

        Route::get('admins/byInstitution/{id}', [InstitutionController::class, 'adminsByInstitution']);

        Route::resource('shifts', ShiftController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('course-types', CourseTypeController::class);
        // Route::resource('classrooms', ClassroomController::class);
        // Route::resource('courses', CourseController::class);
        Route::resource('classroom-students', ClassroomStudentController::class);
        // Route::resource('classes', CourseClassController::class);
        Route::resource('assignment-types', AssignmentTypeController::class);
        // Route::resource('assignments', AssignmentController::class);
        Route::resource('assignment-groups', AssignmentGroupController::class);
    });


    Route::group(['middleware' => 'institution'], function () {
        Route::get('/testInstitution', [AuthController::class, 'test']);

        Route::get('institutions/{id}/dashboard', [InstitutionController::class, 'institutionCount']);

        Route::get('classrooms/{id}/dashboard', [ClassroomController::class, 'classroomCount']);
        Route::get('courses/classes/{id}/dashboard', [CourseController::class, 'courseClassesCount']);

        // Route::resource('users', UsersController::class);
        Route::resource('shifts', ShiftController::class);
        // Route::resource('classrooms', ClassroomController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('course-types', CourseTypeController::class);
        // Route::resource('classes', CourseClassController::class);
        Route::resource('assignment-types', AssignmentTypeController::class);
        // Route::resource('assignments', AssignmentController::class);
        Route::resource('assignment-groups', AssignmentGroupController::class);
        // Route::resource('courses', CourseController::class);


        Route::resource('classroom-students', ClassroomStudentController::class);
    });


    Route::group(['middleware' => 'teacher'], function () {
        Route::resource('assignments', AssignmentController::class);
        Route::resource('assignment-groups', AssignmentGroupController::class);
        Route::get('assignment-groups/byAssignment/{id}', [AssignmentGroupController::class, 'assignmentGroupByAssignment']);



        // Route::resource('courses', CourseController::class);
        // Route::resource('classes', CourseClassController::class);


        // ------------ BigBlueButton Routes ------------ //
        // Route::prefix('bigbluebutton')->group(function () {

        //     // Route::post('create-meeting', [BigBlueButtonController::class, 'createMeeting']);
        //     Route::post('join-as-moderator', [BigBlueButtonController::class, 'joinAsModerator']);
        //     Route::post('join-as-attendee', [BigBlueButtonController::class, 'joinAsAttendee']);

        // });


    });

    Route::group(['middleware' => 'student'], function () {


        Route::prefix('bigbluebutton')->group(function () {

            Route::post('join-as-attendee', [BigBlueButtonController::class, 'joinAsAttendee']);
        });
    });

});

Route::get('bigbluebutton/test/{id}', [BigBlueButtonController::class, 'testCreateMeetingUsers']);
Route::get('bigbluebutton/join-to-meeting', [BigBlueButtonController::class, 'joinToMeeting']);
Route::any('bigbluebutton/callback/{hash}', [BigBlueButtonController::class, 'callback']);

Route::get('socket', [NotificationsController::class, 'testSocket']);

Route::get('/storage/{img}', [ImageController::class, 'index']);

Route::get('/{any}', function ($any) {
    return response()->json(['name' => "Clicking Api", 'version' => 0.1, 'path' => "/$any", 'url' => env('APP_URL')]);
})->where('any', '.*');
