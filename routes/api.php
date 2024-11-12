<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalPlanLevelController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanLevelController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserServiceController;
use App\Models\GoalPlanLevel;
use App\Models\Plan;
use App\Models\PlanLevel;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('/google', GoogleController::class . '@redirectToGoogle');
    Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    Route::get('/{email}/checkEmail', [UserController::class, 'checkEmail']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('update', [AuthController::class, 'update']);
    });
});
Route::group(['prefix' => 'user'], function () {
    Route::get('coachs', [UserController::class, 'coachs']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('update', [UserController::class, 'update']);
    });
});
Route::group(['prefix' => 'service'], function () {
    Route::get('index', [ServiceController::class, 'index']);
});
Route::group(['prefix' => 'userService'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('store/{id}', [UserServiceController::class, 'store']);
    });
});
Route::group(['prefix' => 'chat'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('index', [ChatController::class, 'index']);
        Route::get('show/{chat}', [ChatController::class, 'show']);
        Route::post('store', [ChatController::class, 'store']);
        Route::get('messages/{id}', [MessageController::class, 'index']);
        Route::post('sendMessage', [MessageController::class, 'sendMessage']);
    });
});
Route::group(['prefix' => 'goal'], function () {
    Route::get('index', [GoalController::class, 'index']);
    Route::get('{goal}/show', [GoalController::class, 'show']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('{goal}/update', [GoalController::class, 'update']);
        Route::post('store', [GoalController::class, 'store']);
    });
});
Route::group(['prefix' => 'plan'], function () {
    Route::get('index', [PlanLevelController::class, 'index']);
    Route::get('{PlanLevel}/exercises', [PlanLevelController::class, 'exercise']);
    Route::get('plansForGoal/{ids}', [GoalPlanLevelController::class, 'getPlanForGoals']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('{plan}/update', [PlanController::class, 'update']);
        Route::get('{plan}/show', [PlanController::class, 'show']);
    });
});
Route::group(['prefix' => 'exercise'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('index', [ExerciseController::class, 'index']);
    });
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'target'], function () {
        Route::get('index', [GoalController::class, 'getUserGoals']);
        Route::post('plans/{ids?}', [GoalPlanLevelController::class, 'getPlanForGoalsWithMuscle']);
    });
    Route::group(['prefix' => 'gym'], function () {
        Route::get('index', [GymController::class, 'index']);
        Route::get('show/{gym}', [GymController::class, 'show']);
    });
});
Route::group(['prefix' => 'dashboard'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('index', [UserController::class, 'index']);
    });
    Route::group(['prefix' => 'goal'], function () {
        Route::get('index', [GoalController::class, 'index']);
        Route::get('{goal}/show', [GoalController::class, 'show']);
        Route::post('store', [GoalController::class, 'store']);
        Route::delete('{goal}/destroy', [GoalController::class, 'destroy']);
    });
    Route::group(['prefix' => 'plan'], function () {
        Route::get('{PlanLevel}/exercises', [PlanLevelController::class, 'exercise']);
        Route::get('index', [PlanLevelController::class, 'getPlans']);
        Route::get('{plan}/show', [PlanController::class, 'show']);
        Route::get('plansForGoal/{ids}', [GoalPlanLevelController::class, 'getPlanForGoals']);
        Route::post('store', [PlanController::class, 'store']);
    });
});
