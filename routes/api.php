<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalPlanLevelController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanLevelController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserServiceController;
use App\Models\GoalPlanLevel;
use App\Models\Plan;
use App\Models\PlanLevel;
use Illuminate\Support\Facades\Route;

Route::get('getLastTimeUpdateDatabase', [UserController::class, 'getLastTimeUpdateDatabase']);
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
        Route::get('show/{user}', [UserController::class, 'show']);
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
    Route::get('{goal}/show/{id?}', [GoalController::class, 'show']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('{goal}/update', [GoalController::class, 'update']);
        Route::post('store', [GoalController::class, 'store']);
    });
});
Route::group(['prefix' => 'plan'], function () {
    Route::get('index', [PlanLevelController::class, 'index']);
    Route::get('plansForGoal/{ids}', [GoalPlanLevelController::class, 'getPlanForGoals']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('{PlanLevel}/{day}/{week}/exercises', [PlanLevelController::class, 'exercise']);
        Route::post('{day}/{week}/meals', [PlanLevelController::class, 'meal']);
        Route::post('{plan}/update', [PlanController::class, 'update']);
        Route::get('{id}/show', [PlanLevelController::class, 'show']);
    });
});
Route::group(['prefix' => 'exercise'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('index', [ExerciseController::class, 'index']);
        Route::get('{exercise}/show', [ExerciseController::class, 'show']);
    });
});
Route::group(['prefix' => 'meal'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('{meal}/show', [MealController::class, 'show']);
    });
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'target'], function () {
        Route::get('index', [PlanLevelController::class, 'getUserPlans']);
        Route::post('plans/{ids?}', [GoalPlanLevelController::class, 'getPlanForGoalsWithMuscle']);
        Route::get('insert/{id}', [GoalPlanLevelController::class, 'insert']);
        Route::post('store', [TargetController::class, 'store']);
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
        Route::get('{id}/showGoal', [GoalController::class, 'showGoal']);
        Route::post('store', [GoalController::class, 'store']);
        Route::post('{goal}/update', [GoalController::class, 'update']);
        Route::delete('{goal}/destroy', [GoalController::class, 'destroy']);
    });
    Route::group(['prefix' => 'plan'], function () {
        Route::get('{PlanLevel}/exercises', [PlanLevelController::class, 'getExerciseForPlan']);
        Route::get('index', [PlanController::class, 'index']);
        Route::get('{planLevel}/show', [PlanLevelController::class, 'showPlan']);
        Route::get('{plan}/showPlan', [PlanController::class, 'show']);
        Route::post('{plan}/update', [PlanController::class, 'update']);
        Route::get('plansForGoal/{ids}', [GoalPlanLevelController::class, 'getPlanForGoals']);
        Route::post('store', [PlanController::class, 'store']);
        Route::delete('{planLevel}/destroy', [PlanLevelController::class, 'destroy']);
    });
    Route::group(['prefix' => 'exercise'], function () {
        Route::get('index', [ExerciseController::class, 'index']);
        Route::post('store', [ExerciseController::class, 'store']);
        Route::post('{exercise}/update', [ExerciseController::class, 'update']);
        Route::get('{exercise}/show', [ExerciseController::class, 'show']);
        Route::delete('{exercise}/destroy', [ExerciseController::class, 'destroy']);
    });
    Route::group(['prefix' => 'category'], function () {
        Route::get('index', [CategoryController::class, 'index']);
        Route::post('store', [CategoryController::class, 'store']);
        Route::post('{category}/update', [CategoryController::class, 'update']);
        Route::get('{category}/show', [CategoryController::class, 'show']);
        Route::delete('{category}/destroy', [CategoryController::class, 'destroy']);
    });
    Route::group(['prefix' => 'service'], function () {
        Route::get('index', [ServiceController::class, 'index']);
        Route::post('store', [ServiceController::class, 'store']);
        Route::post('{service}/update', [ServiceController::class, 'update']);
        Route::get('{service}/show', [ServiceController::class, 'show']);
        Route::delete('{service}/destroy', [ServiceController::class, 'destroy']);
    });
    Route::group(['prefix' => 'meal'], function () {
        Route::get('index', [MealController::class, 'index']);
        Route::post('store', [MealController::class, 'store']);
        Route::post('{meal}/update', [MealController::class, 'update']);
        Route::get('{meal}/show', [MealController::class, 'show']);
        Route::delete('{meal}/destroy', [MealController::class, 'destroy']);
    });
    Route::group(['prefix' => 'gym'], function () {
        Route::get('index', [GymController::class, 'getIndex']);
        Route::post('store', [GymController::class, 'store']);
        Route::post('{gym}/update', [GymController::class, 'update']);
        Route::get('{gym}/show', [GymController::class, 'showGym']);
        Route::delete('{gym}/destroy', [GymController::class, 'destroy']);
        Route::get('section', [SectionController::class, 'index']);
    });
    Route::group(['prefix' => 'section'], function () {
        Route::get('index', [SectionController::class, 'index']);
    });
});
