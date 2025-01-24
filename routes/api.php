<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoalPlanController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserServiceController;
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
        Route::get('profile', [UserController::class, 'profile']);
        Route::get('deleteAccount', [UserController::class, 'deleteAccount']);
        Route::post('editProfile', [UserController::class, 'editProfile']);
        Route::post('update', [UserController::class, 'update']);
        Route::post('editScheduling', [TargetController::class, 'editScheduling']);
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
    Route::get('{goal}/show', [GoalController::class, 'show']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('{goal}/update', [GoalController::class, 'update']);
        Route::post('store', [GoalController::class, 'store']);
    });
});
Route::group(['prefix' => 'plan'], function () {
    Route::get('plansForGoal/{ids}', [GoalPlanController::class, 'getPlanForGoals']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('index', [PlanController::class, 'index']);
        Route::post('{PlanLevel}/{day}/{week}/exercises', [PlanController::class, 'exercise']);
        Route::get('sleep', [PlanController::class, 'getSleep']);
        Route::get('water', [PlanController::class, 'getWater']);
        Route::post('{day}/{week}/meals', [PlanController::class, 'meal']);
        Route::post('{plan}/update', [PlanController::class, 'update']);
        Route::post('{id}/show', [PlanController::class, 'showPlan']);
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
        Route::get('category', [CategoryController::class, 'index']);
    });
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'target'], function () {
        Route::get('index', [PlanController::class, 'getUserPlans']);
        Route::get('plans', [GoalPlanController::class, 'getPlanForGoalsWithMuscle']);
        Route::get('insert/{id}', [GoalPlanController::class, 'insert']);
        Route::get('getDateGoal', [GoalPlanController::class, 'getDateGoal']);
        Route::post('store', [TargetController::class, 'store']);
        Route::post('storeSleep', [TargetController::class, 'storeSleep']);
        Route::post('storeWater', [TargetController::class, 'storeWater']);
        Route::post('storeExersice', [TargetController::class, 'storeE']);
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
    Route::group(['prefix' => 'admin'], function () {
        Route::post('active_goal', [TargetController::class, 'update']);
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
        Route::get('{PlanLevel}/exercises', [PlanController::class, 'getExerciseForPlan']);
        Route::get('index', [PlanController::class, 'index']);
        // Route::get('{planLevel}/show', [PlanLevelController::class, 'showPlan']);
        Route::get('{plan}/showPlan', [PlanController::class, 'show']);
        Route::post('{plan}/update', [PlanController::class, 'update']);
        Route::get('plansForGoal/{ids}', [GoalPlanController::class, 'getPlanForGoals']);
        Route::post('store', [PlanController::class, 'store']);
        Route::delete('{planLevel}/destroy', [GoalPlanController::class, 'destroy']);
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
