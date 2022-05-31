<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
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
Route::post('/login',[App\Http\Controllers\Api\V1\AuthController::class,'login']);
Route::get('/init-exam',[App\Http\Controllers\Api\V1\ExamenController::class,'initExam']);
Route::get('/exams/{user_id}/{subject_id}',[App\Http\Controllers\Api\V1\ExamenController::class,'exams']);
Route::get('/exam/{id}',[App\Http\Controllers\Api\V1\ExamenController::class,'exam']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/logout',[App\Http\Controllers\Api\V1\AuthController::class,'logout']);
    Route::get('/user',function (Request $request) {
        $user = User::with(['roles','permissions'])->where('id', $request->user()->id)->first();
        return response()->json($user);
    });
    Route::apiResource('roles', App\Http\Controllers\Api\V1\RoleController::class);
    Route::apiResource('permissions', App\Http\Controllers\Api\V1\PermissionController::class);
    Route::apiResource('users', App\Http\Controllers\Api\V1\UserController::class);
    Route::apiResource('subjects', App\Http\Controllers\Api\V1\SubjectController::class);
    Route::post('examenes/question',[App\Http\Controllers\Api\V1\ExamenController::class,'deleteQuestion']);
    Route::post('examenes-aplicados',[App\Http\Controllers\Api\V1\ExamenController::class,'appliedExams']);
    Route::apiResource('examenes', App\Http\Controllers\Api\V1\ExamenController::class);
    Route::get('respuestas',[App\Http\Controllers\Api\V1\AnswerController::class,'index']);
    Route::get('respuestas/{id}',[App\Http\Controllers\Api\V1\AnswerController::class,'show']);
});
    Route::post('respuestas',[App\Http\Controllers\Api\V1\AnswerController::class,'store']);


