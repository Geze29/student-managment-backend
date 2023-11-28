<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ChapaController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseStudentEnrollmentController;
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentContactController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Models\Course;
use App\Models\User;
use Chapa\Chapa\Facades\Chapa as Chapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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
Route::middleware('auth:sanctum')->get('/userData', function (Request $request) {
    $user = $request->user();
    switch ($user->role) {
        case 'student':
            return response()->json(User::with('student.student_contact')->find($user->id), 200);
        case 'admin':
            return response()->json(User::with('admin')->find($user->id), 200);
        case 'instructor':
            return response()->json(User::with('instructor')->find($user->id), 200);
        default:
            return response()->json(['message' => 'Unauthenticated user'], 401);
    }
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UserController::class, 'logout']);


    Route::apiResource('student', StudentController::class)->except('store');
    Route::apiResource('studentContact', StudentContactController::class);

    Route::apiResource('instructor', InstructorController::class);

    Route::apiResource('admin', AdminController::class);

    Route::apiResource('enrollment', EnrollmentController::class);
    Route::post('/checkEnrollment', [EnrollmentController::class, 'checkEnrollment']);

    Route::post('/pay', [ChapaController::class, 'pay']);
    Route::get('payment',[PaymentController::class,'index']);
    Route::group(['prefix' => 'enrollmentInfo'], function () {
        Route::get('/students/{id}', [CourseStudentEnrollmentController::class, 'courseTakenStudents']);
        Route::get('/courses/{id}', [CourseStudentEnrollmentController::class, 'studentTakeCourses']);
        Route::get('/instructor/{id}', [CourseStudentEnrollmentController::class, 'instructorCourses']);
    });


    Route::apiResource('schedule', ScheduleController::class)
        ->only(['destroy', 'update', 'index']);
    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/student/{id}', [ScheduleController::class, 'showStudent']);
        Route::get('/instructor/{id}', [ScheduleController::class, 'showInstructor']);
        Route::get('/course/{id}', [ScheduleController::class, 'showCourse']);
        Route::post('/{id}', [ScheduleController::class, 'createSchedule']);
    });

    Route::apiResource('attendance', AttendanceController::class)->only(['show', 'store', 'update']);


    Route::apiResource('course', CourseController::class);

    Route::apiResource('description', DescriptionController::class);
});

Route::post('sendResetCode', [ForgetPasswordController::class, 'sendResetCode']);
Route::post('checkCode', [ForgetPasswordController::class, 'checkCode']);
Route::post('updatePassword/', [UserController::class, 'updatePassword']);

Route::post('login', [UserController::class, 'login']);
Route::post('student', [StudentController::class, 'store']);
Route::apiResource('course', CourseController::class)->only(['show', 'index']);
Route::apiResource('description', DescriptionController::class)->only(['show', 'index']);

Route::post('checkEmail', function (Request $request) {
    $validator = Validator::make($request->only('email'), [
        "email" => "email"
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    if (User::where('email', $request->email)->first()) {
        return response()->json(['message' => 'email exists'], 400);
    } else {
        return response()->json(["message" => "success"], 200);
    }
});

Route::post('checkCourseCode', function (Request $request) {
    $validator = Validator::make($request->only('courseCode'), [
        "courseCode" => "required"
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    if (Course::where('courseCode', $request->courseCode)->first()) {
        return response()->json(['message' => 'courseCode exists'], 400);
    } else {
        return response()->json(["message" => "success"], 200);
    }
});


Route::get('/certificate/{id}',[CertificateController::class,'show']);
Route::get('callback/{reference}', 'App\Http\Controllers\ChapaController@callback')->name('callback');